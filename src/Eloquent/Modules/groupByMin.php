<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByMin extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByMin($fields);

        return $this->builder;
    }
}
