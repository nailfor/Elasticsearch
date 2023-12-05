<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;
use nailfor\Elasticsearch\Query\Pipes\Aggregate\Nested;

class groupByNested extends ModuleGroup
{
    protected string $field = 'nested';
    protected string $type = 'nested';

    protected function getData(string $field, mixed $params): mixed
    {
        return [
            'field' => $field,
            'params' => $params,
        ];
    }

    protected function getGroup($group, $alias, $merge) : array
    {
        $builder = $this->newBuilder();
        $builder->groupBy = $builder->groupPlugin($group['params']);
        $body = [];
        $builder->getAggregations($body);

        $result =  FilterFactory::create($this->type, $group['field']);

        return array_merge($result, $body);
    }

    protected function getPrefix(): string
    {
        return Nested::getType();
    }
}
