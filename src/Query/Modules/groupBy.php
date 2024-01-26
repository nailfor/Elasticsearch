<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;
use nailfor\Elasticsearch\Query\Pipes\Aggregate\Group;

class groupBy extends Module
{
    use Traits\Groups;

    protected string $field = 'groups';

    /**
     * Parse request and build aggregation.
     * @param array $group
     */
    protected function getGroup($group, $alias, $merge): array
    {
        $field = $group['field'] ?? $group;
        $result = FilterFactory::create('terms', [$field, $this->builder->limit]);

        $aggs = $group['aggs'] ?? [];
        $type = Group::getType();
        if ($aggs) {
            foreach ($aggs as $grp => $agg) {
                if ($grp === 'groups') {
                    foreach ($agg as $subAlias => $subGroup) {
                        $result['aggs'][$type . $subAlias] = $this->getGroup($subGroup, $subAlias, null);
                    }
                    continue;
                }

                if (is_string($agg)) {
                    $result['aggs'][$type . $grp] = $this->getGroup($agg, $grp, null);
                    continue;
                }

                foreach ($agg as $grp => $data) {
                    $result['aggs'][$grp] = $data;
                }
            }
        }

        return $result;
    }

    protected function getPrefix(): string
    {
        return Group::getType();
    }
}
