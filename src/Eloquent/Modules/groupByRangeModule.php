<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupByRangeModule extends Module
{
    public function handle($fields)
    {
        $this->query->groupByRange($fields);

        return $this->builder;
    }
}
