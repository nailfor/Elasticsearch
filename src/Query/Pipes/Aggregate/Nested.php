<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

class Nested extends AbstractAggregator
{
    public const TYPE = 'nested';

    protected function do(array $data): array
    {
        $data = parent::do($data);

        return $this->getBucket($data);
    }
}
