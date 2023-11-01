<?php
namespace nailfor\Elasticsearch\Query\Modules;

class setRawQuery extends Module
{
    public function handle($params)
    {
        $this->builder->rawQuery = $params[0];

        return $this->builder;
    }
    
    public function getBody()
    {
        return $this->builder->rawQuery;
    }
}
