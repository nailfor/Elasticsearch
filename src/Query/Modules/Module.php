<?php

namespace nailfor\Elasticsearch\Query\Modules;

use Elastic\Elasticsearch\Client;
use nailfor\Elasticsearch\Eloquent\Builder;
use nailfor\Elasticsearch\Factory\FilterFactory;
use nailfor\Elasticsearch\Query\QueryBuilder;

abstract class Module implements ModuleInterface
{
    protected QueryBuilder $builder;
    protected array $skip = [];
    
    
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function newEloquentQuery(): Builder
    {
        $queryBuilder = $this->builder->connection->query();

        return new Builder($queryBuilder);
    }

    public function newBuilder(): QueryBuilder
    {
        $query = $this->newEloquentQuery();

        return $query->getQuery();
    }

    protected function getClient(): Client
    {
        return $this->builder->connection->getClient();
    }
    
    /**
     * Return where params
     * @return array
     */
    protected function getWhereFilter($wheres = null) : array
    {
        $res = [];
        $wheres = $wheres ?? $this->builder->wheres;
        foreach($wheres ?? [] as $where) {
            $type = $where['type'];
            $operator = $where['operator'] ?? null;
            if (in_array($operator, $this->skip)) {
                continue;
            }
            
            $res[] = $this->getFilter($type, $where);
        }
        
        return $res;
    }

    protected function getFilter($type, $where) : array
    {
        return FilterFactory::create($type, $where);
    }
}
