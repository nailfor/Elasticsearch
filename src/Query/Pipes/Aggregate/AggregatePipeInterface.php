<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;
use nailfor\Elasticsearch\Query\Pipes\PipeInterface;

interface AggregatePipeInterface extends PipeInterface
{
    public static function getAggregate(array $aggregate): array;

    public static function getType(): string;
}
