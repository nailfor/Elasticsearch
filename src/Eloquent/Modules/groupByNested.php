<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByNested extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByNested($fields);

        return $this->builder;
    }
}
