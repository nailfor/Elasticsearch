<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByRange extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByRange($fields);

        return $this->builder;
    }
}
