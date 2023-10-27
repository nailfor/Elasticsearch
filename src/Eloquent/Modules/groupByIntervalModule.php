<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupByIntervalModule extends Module
{
    public function handle($fields)
    {
        $this->query->groupByInterval($fields);

        return $this->query;
    }
}
