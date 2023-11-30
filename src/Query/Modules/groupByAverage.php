<?php

namespace nailfor\Elasticsearch\Query\Modules;

class groupByAverage extends ModuleGroup
{
    protected string $field = 'average';
    protected string $type = 'average';
    
    protected function getGroup($group, $alias, $merge) : array
    {
        if (!$merge) {
            return $group;
        }
        
        $aggs = $merge['aggs'] ?? [];
        
        $res = array_merge($aggs, [
            "{$alias}_{$this->field}"    => $group,
        ]);
                
        $merge['aggs'] = $res;
                
        return $merge;
    }
}
