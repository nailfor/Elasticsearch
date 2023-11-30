<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;
use nailfor\Elasticsearch\Query\QueryBuilder;

interface ModuleInterface
{
    public function newEloquentQuery(): Builder;

    public function newBuilder(): QueryBuilder;
}
