<?php

namespace nailfor\Elasticsearch\Query\Modules;

use Elastic\Elasticsearch\Client;
use nailfor\Elasticsearch\Factory\FilterFactory;
use nailfor\Elasticsearch\Query\QueryBuilder;

class Module
{
    protected QueryBuilder $builder;
    protected array $skip = [];
    
    
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }

    protected function getClient(): Client
    {
        return $this->builder->connection->getClient();
    }
    
    /**
     * Return where params
     * @return array
     */
    protected function getWhereFilter($wheres = []) : array
    {
        $res = [];
        $wheres = $wheres ?? $this->builder->wheres;
        foreach($wheres ?? [] as $where) {
            $type = $where['type'];
            $operator = $where['operator'] ?? null;
            if (in_array($operator, $this->skip)) {
                continue;
            }
            $filter = $this->getFilter($type, $where);
            
            $res = array_merge($res, $filter);
        }
        
        return $res;
    }

    protected function getFilter($type, $where) : array
    {
        return FilterFactory::create($type, $where);
    }
}
