<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupByHistogramModule extends Module
{
    public function handle($fields)
    {
        $this->query->groupByHistogram($fields);

        return $this->query;
    }
}
