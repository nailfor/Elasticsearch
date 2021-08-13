<?php

namespace nailfor\Elasticsearch\Query;

use nailfor\Elasticsearch\Query\DSL\Filter;
use nailfor\Elasticsearch\Query\DSL\existsFilter;
use nailfor\Elasticsearch\ClassIterator;
use nailfor\Elasticsearch\GetSetTrait;
use nailfor\Elasticsearch\ModuleTrait;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Arr;

/**
 * Elasticsearch
 *
 */
class QueryBuilder extends Builder
{
    use GetSetTrait;
    use ModuleTrait;
    
    protected $count;
    
    public function __construct(ConnectionInterface $connection, Grammar $grammar = null, Processor $processor = null)
    {
        $this->init(__DIR__.'/Modules', 'Module', $this);
        return parent::__construct($connection, $grammar, $processor);
    }
    
    /**
     * {@inheritdoc}
     */
    public function get($columns = ['*'])
    {
        $res = $this->onceWithColumns(Arr::wrap($columns), function () {
            $res = $this->runSelect();
            return $this->processor->processSelect($this, $res);
        });
        
        return collect($res);
    }

    /**
     * {@inheritdoc}
     */
    protected function runSelect()
    {
        $params = $this->getParams();
        $client = $this->connection->getClient();

        $res = $client->search($params);
        $this->count = $res['hits']['total']['value'];
        
        $aggs = $res['aggregations'] ?? [];
        if ($aggs) {
            return $this->elasticAggregate($aggs);
        }
        
        $res = $res['hits']['hits'] ?? [];
        
        $items = array_map(function ($item) {
            $res = $item['_source'];
            $res['_id'] = $item['_id'];
            return $res;
        }, $res);
        
        return $items;
    }
    
    /**
     * Return linear array
     * @param type $aggs
     * @return array
     */
    protected function elasticAggregate($aggs) : array
    {
        $res = [];
        foreach($aggs as $agg => $items) {
            $item = $this->getBuckets($items, $agg);
            $res = array_merge($res, $item);
        }
        
        return $res;
    }
    
    /**
     * Return bucket of loads
     * @param type $items
     * @param type $agg
     * @param type $append
     * @return array
     */
    protected function getBuckets($items, $agg, $append = []) : array
    {
        $val = $items['value'] ?? 0;
        if ($val)  {
            return [
                array_merge($append, [
                    'count' => $val,
                ]),
            ];
        }
        
        $res = [];
        $buckets = $items['buckets'] ?? [];
        foreach ($buckets as $item) {
            $itBucket = 0;
            foreach ($item as $key => $val) {
                $bck = $val['buckets'] ?? 0;
                $v = $val['value'] ?? 0;
                if (is_array($val) && ($bck || $v)) {
                    $app = array_merge($append, [$agg => $item['key']]);
                    $bucket = $this->getBuckets($val, $key, $app);
                    $res = array_merge($res, $bucket);
                    $itBucket = 1;
                }
            }
            
            if (!$itBucket) {
                $res[] = array_merge($append, [
                    $agg    => $item['key'],
                    'count' => $item['doc_count'],
                ]);
            }
        }

        return $res;
    }
    
    /**
     * Return count of records
     * @return type
     */
    protected function getCount()
    {
        return $this->count;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCountForPagination($columns = ['*'])
    {
        $results = $this->runPaginationCountQuery($columns);
        
        return $results;
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
        $body = [];
        $this->runModule('getBody', $body, '');
        
        if (!$body) {
            $bool = [];
            $this->runModule('getMust', $bool, 'must', true);
            $this->runModule('getMustNot', $bool, 'mustNot', true);
            $body = [
                'query' => [
                    'bool' => $bool,
                ],
            ];
            
            $this->runModule('getGroups', $body, 'aggs');
            $this->runModule('getSort', $body, 'sort');
        }
        
        $params = [
            'index' => $this->from,
            'body' => $body,
        ];
        
        if ($this->offset) {
            $params['from'] = $this->offset;
        }

        if ($this->limit) {
            $params['size'] = $this->limit;
        }
        
        return $params;
    }
    
    protected function runModule($name, &$body, $field, $add = false) 
    {
        $res = [];
        $modules = $this->getModules($name);
        foreach ($modules as $module) {
            $res = $module->$name($res);
            if ($add && $res) {
                if ($field) {
                    $body[$field][] = $res;
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
     * Return filter by name
     * @param type $type
     * @param type $params
     * @return type
     */
    public function getFilterByType($type, $params) 
    {
        $namespace = __NAMESPACE__;
        $class = "$namespace\\DSL\\{$type}Filter";

        if (!class_exists($class)) {
            $class = Filter::class;
        }
        $f = new $class($params);
        
        return $f->getFilter();
    }
    
    /**
     * {@inheritdoc}
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
     * Create uniq key
     * @param type $values
     * @param type $sequence
     * @return int
     */
    public function getElasticKey($values, $sequence)
    {
        $id = $values[$sequence] ?? 0;
        if (!$id) {
            return 0;
        }
            
        return "{$this->from}_{$id}";
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete($id = null)
    {
        $client = $this->connection->getClient();
        
        $_id = $id;
        if (!$_id) {
            foreach($this->wheres ?? [] as $where) {
                if ($where['column'] == '_id') {
                    $_id = $where['value'];
                    break;
                }
            }
            
            if (!$_id) {
                return;
            }
        }
        
        $params = [
            'index' => $this->from,
            'id' => $_id
        ];
        
        return $client->delete($params);
    }

    /**
     * Create an elasticsearch index
     * @param array $settings
     * @param array $mappingProperties
     * @param int $shards
     * @param int $replicas
     * @return type
     */
    public function createIndex(array $settings = [],array $mappingProperties = [], int $shards = null, int $replicas = null) 
    {
        $client = $this->connection->getClient();
        
        $index = [
            'index' => $this->from,
        ];
        
        if ($settings) {
            $index['body']['settings'] = $settings;
        }

        if ($mappingProperties) {
            $index['body']['mappings'] = [
                'properties' => $mappingProperties,
            ];
        }        
        
        if (!is_null($shards)) {
            $index['body']['settings']['number_of_shards'] = $shards;
        }

        if (!is_null($replicas)) {
            $index['body']['settings']['number_of_replicas'] = $replicas;
        }

        return $client->indices()->create($index);
    }
    
    /**
     * {@inheritdoc}
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
