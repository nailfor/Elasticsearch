<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;

class groupBy extends Module
{
    protected string $field = 'groups';

    use Traits\Groups;
    
    /**
     * Parse request and build aggregation
     * @param array $group
     * @return array
     */
    protected function getGroup($group, $alias, $merge) : array
    {
        $field = $group['field'] ?? $group;
        $result = FilterFactory::create('terms', [$field, $this->builder->limit]);
        
        $aggs = $group['aggs'] ?? [];
        if ($aggs) {
            foreach ($aggs as $grp => $agg) {
                if ($grp === 'groups') {
                    foreach ($agg as $subAlias => $subGroup) {
                        $result['aggs'][$subAlias] = $this->getGroup($subGroup, $subAlias, null);
                    }
                    continue;
                }

                if (is_string($agg)) {
                    $result['aggs'][$grp] = $this->getGroup($agg, $grp, null);
                    continue;
                }

                foreach ($agg as $group => $data) {
                    $result['aggs'][$group] = $data;
                }
            }
        }
        
        return $result;
    }
}
