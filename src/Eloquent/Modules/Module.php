<?php
namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Query\QueryBuilder as Query;

abstract class Module 
{
    protected $query;
    
    public function __construct(Query $query)
    {
        $this->query = $query;
    }    
    
    abstract public function handle($fields);

}
