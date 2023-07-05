<?php
namespace nailfor\Elasticsearch\Query\Modules;

class Module
{
    protected $builder;
    protected $skip = [];
    
    
    public function __construct($builder)
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
            $filter = $this->builder->getFilterByType($type, $where);
            
            $res = array_merge($res, $filter);
        }
        
        return $res;
    }    
}
