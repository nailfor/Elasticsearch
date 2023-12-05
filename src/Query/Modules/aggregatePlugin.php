<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\AbstractAggregator;

class aggregatePlugin extends Module
{
    public function handle(array $params): array
    {
        $response = $params[0] ?? [];

        $data = $response['aggregations'] ?? [];
        if (!$data) {
            return [];
        }

        return AbstractAggregator::getAggregate($data);
    }
}
