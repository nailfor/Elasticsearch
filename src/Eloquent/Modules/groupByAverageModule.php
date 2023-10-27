<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupByAverageModule extends Module
{
    public function handle($fields)
    {
        $this->query->groupByAverage($fields);

        return $this->query;
    }
}
