<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Average;

class groupByAverage extends ModuleGroup
{
    protected string $field = 'average';

    protected string $type = 'average';

    protected function getGroup(array|string $group, string $alias, ?array $merge): array
    {
        if (!$merge) {
            return $group;
        }

        $aggs = $merge['aggs'] ?? [];

        $res = array_merge($aggs, [
            "{$alias}_{$this->field}" => $group,
        ]);

        $merge['aggs'] = $res;

        return $merge;
    }

    protected function getPrefix(): string
    {
        return Average::getType();
    }
}
