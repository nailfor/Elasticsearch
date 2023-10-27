<?php

namespace nailfor\Elasticsearch\Query\Modules;

class setQuery extends Module
{
    public function handle(array $params)
    {
        $params = reset($params);
        $this->builder->query = array_shift($params);
        $this->builder->params = $params[0] ?? null;

        return $this->builder;
    }
}
