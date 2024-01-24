<?php

namespace nailfor\Elasticsearch\Factory;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\AggregatePipeInterface;

class AggregateFactory
{
    public static function handle(array $aggregate): array
    {
        $hub = PipeFactory::create(AggregatePipeInterface::class);

        $data = $hub->pipe([
            'data' => $aggregate
        ]);

        return $data['result'] ?? [];
    }    
}
