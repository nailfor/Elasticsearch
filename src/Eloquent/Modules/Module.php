<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;
use nailfor\Elasticsearch\Query\QueryBuilder;

abstract class Module 
{
    protected QueryBuilder $query;

    protected Builder $builder;
    
    public function __construct(array $params)
    {
        $this->query = $params['query'];
        $this->builder = $params['builder'];
    }
    
    abstract public function handle($fields);
}
