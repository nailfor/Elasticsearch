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

    protected function getGroup(array|string $group, string $alias, ?array $merge): array
    {
        $builder = $this->newBuilder();
        $builder->groupBy = $builder->groupPlugin($group['params']);
        $body = [];
        $builder->getAggregations($body);
        //TODO: Do fix from $query->limit(100)
        $limit = config('elasticsearch.groupsize', 100);
        if ($limit) {
            $aggs = $body['aggs'] ?? [];
            $key = array_key_first($aggs);
            $data = $aggs[$key];
            $data['terms']['size'] = $limit;
            $body['aggs'][$key] = $data;
        }

        $result = FilterFactory::create($this->type, $group['field']);

        return array_merge($result, $body);
    }

    protected function getPrefix(): string
    {
        return Nested::getType();
    }
}
