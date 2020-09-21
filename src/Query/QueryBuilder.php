<?php

namespace nailfor\Elasticsearch\Query;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;


/**
 * Elasticsearch
 *
 */
class QueryBuilder extends Builder
{
    protected $query;
    
    public function get($columns = ['*'])
    {
        $res = $this->onceWithColumns(Arr::wrap($columns), function () {
            $res = $this->runSelect();
            return $this->processor->processSelect($this, $res);
        });
        
        return collect($res);
    }
    
    public function setQuery($query)
    {
        $this->query = $query;
    }
    
    protected function runSelect()
    {
        $client = $this->connection->getClient();

        $query = [
            "bool" => [
                "must" => [
                    "multi_match" => [
                        "query" => $this->query,
                        "fields" => $this->columns,
                        "operator" => "and"
                    ],
                ],
            ],
        ];
        
        foreach($this->wheres as $where) {
            $filter[$where['column']] = $where['value'];
            $query['bool']['filter'] = [
                'term' => $filter,
            ];
        }
        
        $params = [
            "index" => $this->from,
            "body" => [
                "query" => $query,
            ],
        ];
        
        if ($this->limit) {
            $params['size'] = $this->limit;
        }
        
        
        $res = $client->search($params);
        $res = $res['hits']['hits'] ?? [];
        
        $items = array_map(function ($item) {
            return $item['_source'];
        }, $res);
        
        return $items;
    }
}
