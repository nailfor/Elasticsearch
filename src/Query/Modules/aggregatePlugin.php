<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\AggregateFactory;

class aggregatePlugin extends Module
{
    public function handle(array $params): array
    {
        $response = $params[0] ?? [];

        $data = $response['aggregations'] ?? [];
        if (!$data) {
            return [];
        }

        return AggregateFactory::handle($data);
    }
}
