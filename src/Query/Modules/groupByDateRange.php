<?php

namespace nailfor\Elasticsearch\Query\Modules;

class groupByDateRange extends groupByRange
{
    protected string $field = 'ranges';
    protected string $type = 'rangeDate';
    
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
