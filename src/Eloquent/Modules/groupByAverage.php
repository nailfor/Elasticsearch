<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByAverage extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByAverage($fields);

        return $this->builder;
    }
}
