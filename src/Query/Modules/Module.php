<?php
namespace nailfor\Elasticsearch\Query\Modules;

class Module
{
    protected $builder;
    
    public function __construct($builder)
    {
        $this->builder = $builder;
    }
}
