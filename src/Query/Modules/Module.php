<?php
namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;
use nailfor\Elasticsearch\Query\QueryBuilder;

class Module
{
    protected QueryBuilder $builder;
    protected array $skip = [];
    
    
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
    
    /**
     * Return where params
     * @return array
     */
    protected function getWhereFilter() : array
    {
        $res = [];
        foreach($this->builder->wheres ?? [] as $where) {
            $type = $where['type'];
            $operator = $where['operator'] ?? null;
            if (in_array($operator, $this->skip)) {
                continue;
            }
            $filter = FilterFactory::create($type, $where);
            
            $res = array_merge($res, $filter);
        }
        
        return $res;
    }
}
