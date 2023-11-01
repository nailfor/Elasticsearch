<?php
namespace nailfor\Elasticsearch\Query\Modules;
use nailfor\Elasticsearch\Factory\FilterFactory;

class groupByRange extends groupBy
{
    protected string $field = 'ranges';
    protected string $type = 'range';
    
    public function handle($params)
    {
        $groups = $params[0];
        
        $data = $groups[0] ?? '';
        $params = $groups[1] ?? [];
        
        if (is_string($data)) {
            $data = [
                $data => $data,
            ];
        }

        if (!is_array($data)) {
            return $this->builder;
        }
        
        foreach($data as $group=>$field) {
            $this->setField($group, $field, $params);
        }

        return $this->builder;
    }
    
    protected function setField($group, $field, $params)
    {
        $data = array_merge([
            'field' => $field,
        ], $params);
        
        $fieldName = $this->field;
        
        $groups = $this->builder->$fieldName;
        $groups[$group] = FilterFactory::create($this->type, $data);
        $this->builder->$fieldName = $groups;
    }

    protected function getGroup($group, $alias, $merge) : array
    {
        return $group;
    }
}
