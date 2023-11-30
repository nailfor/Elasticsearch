<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByHistogram extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByHistogram($fields);

        return $this->builder;
    }
}
