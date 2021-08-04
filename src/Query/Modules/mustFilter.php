<?php
namespace nailfor\Elasticsearch\Query\Modules;

class mustFilter extends Module
{
    protected $operator = '!=';
    protected $field = 'exists';

    /**
     * Return must params
     * @return array
     */
    public function getMust() : array
    {
        $res = [];
        foreach($this->builder->wheres ?? [] as $where) {
            $type = $where['type'];
            $operator = $where['operator'] ?? null;
            if ($operator == $this->operator) {
                continue;
            }
            $filter = $this->builder->getFilterByType($type, $where);
            
            $res[] = $filter;
        }
        
        $field = $this->field;
        $data = $this->builder->$field;
        if ($data) {
            foreach($data as $exists) {
                $res[] = $this->builder->getFilterByType('exists', $exists);
            }
        }
        
        return $res;
    }
}
