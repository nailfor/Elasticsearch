<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupBy extends Module
{
    protected $field = 'groups';
    
    /**
     * Return groups aggregations
     * @return array
     */
    public function getGroups($groups = []) : array
    {
        $field = $this->field;
        $array = $this->builder->$field;
        if (!is_array($array)) {
            return $groups;
        }
        
        foreach($array as $alias => $group) {
            $groups[$alias] = $this->getGroup($group, $alias, $groups[$alias] ?? 0);
        }
        
        return $groups;
    }
    
    /**
     * Parse request and build aggregation
     * @param type $group
     * @return array
     */
    protected function getGroup($group, $alias, $merge) : array
    {
        $field = $group['field'] ?? $group;
        $res = $this->builder->getFilterByType('terms', [$field, $this->builder->limit]);
        
        $aggs = $group['aggs'] ?? [];
        foreach($aggs as $alias => $field) {
            if (is_numeric($alias)) {
                $alias = $field;
            }

            $res['aggs'][$alias] = $this->builder->getFilterByType('terms', [$field, $this->builder->limit]);
        }
        
        return $res;
    }    
}
