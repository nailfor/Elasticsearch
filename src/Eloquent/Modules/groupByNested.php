<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupByNested extends Module
{
    public function handle($fields)
    {
        $this->query->groupByNested($fields);

        return $this->builder;
    }
}
