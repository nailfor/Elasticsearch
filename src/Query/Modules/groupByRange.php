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
        $fieldName = $this->field;
        
        $groups = $this->builder->$fieldName;
        $groups[$group] = $this->getData($field, $params);
        $this->builder->$fieldName = $groups;
    }

    protected function getData(string $field, mixed $params): mixed
    {
        $data = array_merge([
            'field' => $field,
        ], $params);

        return FilterFactory::create($this->type, $data);
    }

    protected function getGroup($group, $alias, $merge) : array
    {
        return $group;
    }
}
