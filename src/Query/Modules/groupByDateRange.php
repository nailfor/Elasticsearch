<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\DateRange;

class groupByDateRange extends ModuleGroup
{
    protected string $field = 'dataranges';

    protected string $type = 'rangeDate';

    protected function getGroup($group, $alias, $merge): array
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

    protected function getPrefix(): string
    {
        return DateRange::getType();
    }
}
