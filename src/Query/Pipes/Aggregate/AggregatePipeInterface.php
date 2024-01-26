<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

use nailfor\Elasticsearch\Query\Pipes\PipeInterface;

interface AggregatePipeInterface extends PipeInterface
{
    public static function getType(): string;
}
