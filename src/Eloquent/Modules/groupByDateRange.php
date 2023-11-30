<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByDateRange extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByDateRange($fields);

        return $this->builder;
    }
}
