<?php

namespace nailfor\Elasticsearch\Query;

use nailfor\Elasticsearch\Query\DSL\Filter;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

/**
 * Elasticsearch
 *
 */
class QueryBuilder extends Builder
{
    protected $rawQuery;
    protected $query;
    protected $count;
    protected $res;
    
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
     * Set search query
     * @param type $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * Set raw query
     * @param type $query
     */
    public function setRawQuery($query)
    {
        $this->rawQuery = $query;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function runSelect()
    {
        $params = $this->getParams();
        $client = $this->connection->getClient();

        $res = $client->search($params);
        $this->res = $res;
        $this->count = $res['hits']['total']['value'];
        
        $res = $res['hits']['hits'] ?? [];
        
        $items = array_map(function ($item) {
            $res = $item['_source'];
            $res['_id'] = $item['_id'];
            return $res;
        }, $res);
        
        return $items;
    }
    
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
        $bool['must'][] = $this->getMust();
        $filter = $this->getFilter();
        if ($filter) {
            $bool['must'][] = $filter;
        }
        
        if ($this->rawQuery) {
            $body = $this->rawQuery;
        }
        else {
            $body = [
                'query' => [
                    'bool' => $bool,
                ],
            ];
        }
        
        $params = [
            'index' => $this->from,
            'body' => $body,
        ];
        
        $sort = $this->getSort();
        if ($sort) {
            $params['body']['sort'] = $sort;
        }
        
        if ($this->offset) {
            $params['from'] = $this->offset;
        }

        if ($this->limit) {
            $params['size'] = $this->limit;
        }
        
        return $params;
    }
    
    /**
     * Return must params
     * @return array
     */
    protected function getMust() : array
    {
        $columns = $this->columns ? : ['*'];
        $query = $this->query;

        $res = [];
        if ($query) {
            $match = [
                'fields'=> $columns,
                'query' => $query,
            ];
            if ($this->columns) {
                $match['operator'] = 'and';
            }
            $res['multi_match'] = $match;
        }
        else {
            $res['match_all'] = (object)[];
        }
        
        return $res;
    }
    
    /**
     * Return filter params
     * @return array
     */
    protected function getFilter() : array
    {
        $res = [];
        foreach($this->wheres ?? [] as $where) {
            $type = $where['type'];
            $namespace = __NAMESPACE__;
            $class = "$namespace\\DSL\\{$type}Filter";
            
            if (!class_exists($class)) {
                $class = Filter::class;
            }
            $f = new $class($where, $res);
            $filter = $f->getFilter();
            
            $res[] = $filter;
        }
        
        return $res;
    }
    
    /**
     * Return sort params
     * @return array
     */
    protected function getSort() : array
    {
        $res = [];
        foreach($this->orders ?? [] as $order) {
            $column = $order['column'];
            $res[] = [
                $column => [
                    'order' => $order['direction'],
                ],
            ];
        }
        
        return $res; 
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
     * Check exists index
     * @return type
     */
    public function existsIndex()
    {
        $client = $this->connection->getClient();

        $index = [
            'index' => $this->from,
            'type'  => '_doc',
        ];
        
        return $client->indices()->existsType($index);
    }

    /**
     * Drop index
     * @return type
     */
    public function deleteIndex()
    {
        $client = $this->connection->getClient();
        
        $index = [
            'index' => $this->from,
        ];
        
        return $client->indices()->delete($index);

    }
}
