<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class groupByInterval extends Module
{
    public function handle($fields): Builder
    {
        $this->query->groupByInterval($fields);

        return $this->builder;
    }
}
