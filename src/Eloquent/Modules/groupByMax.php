<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByMax extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByMax($fields);

        return $this->builder;
    }
}
