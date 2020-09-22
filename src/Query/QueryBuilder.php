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
    protected $query;
    
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
     * {@inheritdoc}
     */
    protected function runSelect()
    {
        $params = $this->getParams();
        $client = $this->connection->getClient();

        $res = $client->search($params);
        $res = $res['hits']['hits'] ?? [];
        
        $items = array_map(function ($item) {
            return $item['_source'];
        }, $res);
        
        return $items;
    }
    
    /**
     * Return request params
     * @return array
     */
    public function getParams() : array
    {
        $bool['must'] = $this->getMust();
        $filter = $this->getFilter();
        if ($filter) {
            $bool['filter'] = $filter;
        }
        
        $params = [
            'index' => $this->from,
            'body' => [
                'query' => [
                    'bool' => $bool,
                ],
            ],
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
        $query = $this->query ? : '';

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
            
            $res = array_merge($res, $filter);
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
}
