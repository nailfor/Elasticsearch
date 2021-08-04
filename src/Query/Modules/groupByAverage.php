<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupByAverage extends groupByRange
{
    protected $field = 'average';
    protected $type = 'average';
    
    protected function getGroup($group, $alias, $merge) : array
    {
        if (!$merge) {
            return $group;
        }
        
        return array_merge($merge, [
            'aggs' => [
                "{$alias}_{$this->field}"    => $group,
            ],
        ]);
    }

}
