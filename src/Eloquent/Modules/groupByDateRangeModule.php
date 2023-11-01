<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupByDateRangeModule extends Module
{
    public function handle($fields)
    {
        $this->query->groupByDateRange($fields);

        return $this->builder;
    }
}
