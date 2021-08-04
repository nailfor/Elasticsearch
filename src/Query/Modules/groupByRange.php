<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupByRange extends groupBy
{
    protected $field = 'ranges';
    protected $type = 'range';
    
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
            return;
        }
        
        foreach($data as $group=>$field) {
            $this->setField($group, $field, $params);
        }
    }
    
    protected function setField($group, $field, $params)
    {
        $data = array_merge([
            'field' => $field,
        ], $params);
        
        $fieldName = $this->field;
        
        $groups = $this->builder->$fieldName;
        $groups[$group] = $this->builder->getFilterByType($this->type, $data);
        $this->builder->$fieldName = $groups;
    }

    protected function getGroup($group, $alias, $merge) : array
    {
        if (!$merge) {
            return $group;
        }
        
        return array_merge($group, [
            'aggs' => [
                "{$alias}_group" => $merge,
           ],
        ]);
    }
    
}
