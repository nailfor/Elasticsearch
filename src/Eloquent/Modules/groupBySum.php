<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupBySum extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupBySum($fields);

        return $this->builder;
    }
}
