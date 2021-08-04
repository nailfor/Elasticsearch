<?php
namespace nailfor\Elasticsearch\Query\Modules;

class setQuery extends Module
{
    public function handle($params)
    {
        $this->builder->query = $params[0];
    }
}
