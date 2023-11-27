<?php

namespace nailfor\Elasticsearch\Query;

use nailfor\Elasticsearch\GetSetTrait;
use nailfor\Elasticsearch\ModuleTrait;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Elasticsearch
 *
 */
class QueryBuilder extends Builder
{
    use GetSetTrait;
    use ModuleTrait;

    /** @var nailfor\Elasticsearch\Connection $connection */
    public $connection;

        protected $count;

    public function __construct(ConnectionInterface $connection, Grammar $grammar = null, Processor $processor = null)
    {
        $this->init(__DIR__.'/Modules', 'Module', $this);
        
        return parent::__construct($connection, $grammar, $processor);
    }
    
    /**
     * @inheritdoc
     */
    public function get($columns = ['*'])
    {
        $res = $this->onceWithColumns(Arr::wrap($columns), function () {
            $items = $this->runSelect();
            return $this->processor->processSelect($this, $items);
        });
        
        return new Collection($res);
    }

    /**
     * @inheritdoc
     */
    protected function runSelect()
    {
        $scrollModule = $this->modules['scroll'];
        $scroll = $scrollModule->getScroll();

        if ($scroll['scroll_id'] ?? 0) {
            return $scrollModule->scroll($scroll);
        }

        $params = $this->getParams();
        $client = $this->connection->getClient();

        $res = $client->search($params);
        $this->count = $res['hits']['total']['value'];
        
        $items = $this->hitsPlugin($res);
        $aggregate = $this->aggregatePlugin($res);
        if (is_array($aggregate)) {
            $items = array_merge($items, $aggregate);
        }
        $suggest = $this->suggestPlugin($res);
        if (is_array($suggest)) {
            $items = array_merge($items, $suggest);
        }

        $scroll_id = $res['_scroll_id'] ?? 0;
        if ($scroll_id) {
            $scroll['scroll_id'] = $scroll_id;
            $this->scroll($scroll);
        }
        
        return $items;
    }

    /**
     * Return count of records
     * @return int
     */
    protected function getCount()
    {
        return $this->count;
    }
    
    /**
     * @inheritdoc
     */
    public function getCountForPagination($columns = ['*'])
    {
        return $this->runPaginationCountQuery($columns);
    }
    
    protected function runPaginationCountQuery($columns = ['*'])
    {
        $without = $this->unions ? ['orders', 'limit', 'offset'] : ['columns', 'orders', 'limit', 'offset'];
        $query = $this->cloneWithout($without)
            ->cloneWithoutBindings($this->unions ? ['order'] : ['select', 'order'])
            ->setAggregate('count', $this->withoutSelectAliases($columns))
        ;
        
        $query->get();
        
        return $query->getCount();
    }
    
    /**
     * Return request params
     * @return array
     */
    public function getParams() : array
    {
        $params = [
            'index' => $this->from,
            'body' => $this->getBody(),
        ];

        $this->runModule('getScroll', $params, 'scroll');

        if ($this->offset) {
            $params['from'] = $this->offset;
        }

        if ($this->limit) {
            $params['size'] = $this->limit;
        }
        
        return $params;
    }

    public function getBody(): array
    {
        $body = [];
        $this->runModule('getBody', $body, '');

        if (!$body) {
            $query = [];
            $this->runModule('getQueryBody', $query, '', true);

            $body = [
                'query' => reset($query),
            ];
            
            $this->runModule('getSuggest', $body, 'suggest');
            $this->runModule('getGroups', $body, 'aggs');
            $this->runModule('getSort', $body, 'sort');
        }

        return $body;
    }

    public function getBool(): array
    {
        $bool = [];
        $this->runModule('getMust', $bool, 'must', true);
        $this->runModule('getMustNot', $bool, 'mustNot', true);

        return $bool;
    }

    protected function runModule($name, &$body, $field, $add = false) 
    {
        $res = [];
        $modules = $this->getModules($name);
        foreach ($modules as $module) {
            $res = $module->$name($res);
            if ($add && $res) {
                if ($field) {
                    $body[$field] = array_merge($body[$field] ?? [], $res);
                }
                else {
                    $body[] = $res;
                }
            }
        }
        
        if ($res  && !$add) {
            if ($field) {
                $body[$field] = $res;
            }
            else {
                $body = $res;
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $client = $this->connection->getClient();
        $params = [
            'index' => $this->from,
            'body' => $values,
        ];
        
        $id = $this->getElasticKey($values, $sequence);
        if ($id) {
            $params['id'] = $id;
        }
        
        $res = $client->index($params);
        return $res['_id'] ?? false;
    }
    
    /**
     * @inheritdoc
     */
    public function update(array $values)
    {
        $this->updatePlugin($values);
    }
    
    /**
     * Create uniq key
     * @param array $values
     * @param string $sequence
     * @return string
     */
    public function getElasticKey(array $values, $sequence): string
    {
        return $values[$sequence] ?? '';
    }
    
    /**
     * @inheritdoc
     */
    public function delete($id = null)
    {
        return $this->deletePlugin($id);
    }

    /**
     * @inheritdoc
     */
    public function groupBy(...$groups)
    {
        $cnt = count($groups);
        if ($cnt == 1) {
            $group = $groups[0];
            if (is_string($group)) {
                $group = [$group => $group];
            }
            return parent::groupBy($group);
        }
        
        $group = '';
        for($i=0; $i<$cnt; $i++) {
            $data = $groups[$i];
            if (is_string($data)) {
                $group = $data;
                $field = $this->groups[$group] ?? '';
                if (!$field) {
                    parent::groupBy([$group => $group]);
                }
            }
            
            if ($group && is_array($data)) {
                $field = $this->groups[$group] ?? '';
                $this->groups[$group] = [
                    'field' => $field,
                    'aggs' => $data,
                ];
                $group = '';
            }
        }
        
        return $this;
    }
}
