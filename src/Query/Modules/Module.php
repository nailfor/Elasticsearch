<?php

namespace nailfor\Elasticsearch\Query\Modules;

use Elastic\Elasticsearch\Client;
use nailfor\Elasticsearch\Eloquent\Builder;
use nailfor\Elasticsearch\Query\QueryBuilder;

abstract class Module implements ModuleInterface
{
    protected QueryBuilder $builder;

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
}
