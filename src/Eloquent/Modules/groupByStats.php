<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByStats extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByStats($fields);

        return $this->builder;
    }
}
