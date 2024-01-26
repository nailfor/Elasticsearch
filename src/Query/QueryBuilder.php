<?php

namespace nailfor\Elasticsearch\Query;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use nailfor\Elasticsearch\GetSetTrait;
use nailfor\Elasticsearch\ModuleTrait;
use nailfor\Elasticsearch\Query\Modules\ModuleInterface;

/**
 * Elasticsearch.
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
        $this->init(ModuleInterface::class, $this);

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
     * Return count of records.
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
     * Return request params.
     */
    public function getParams(): array
    {
        $body = $this->getBody();
        $this->getAggregations($body);

        $params = [
            'index' => $this->from,
            'body' => $body,
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
        $this->runModule('getBody', $body, 'body', true);

        return $body['body'] ?? [];
    }

    public function getAggregations(array &$body): void
    {
        $this->runModule('getGroups', $body, 'aggs');
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
                } else {
                    $body[] = $res;
                }
            }
        }

        if ($res && !$add) {
            if ($field) {
                $body[$field] = $res;
            } else {
                $body = $res;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function insert(array $values)
    {
        return $this->insertPlugin($values);
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
     * Create uniq key.
     * @param string $sequence
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
        $groups = $this->groupPlugin(...$groups);
        parent::groupBy($groups);

        $group = $this->groupBy ?? [];
        $group['groups'] = $this->groups;
        $this->groupBy = $group;

        return $this;
    }
}
