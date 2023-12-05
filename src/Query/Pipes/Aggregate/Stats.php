<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

class Stats extends AbstractAggregator
{
    public const TYPE = 'stats';

    protected function do(array $data): array
    {
        $data = parent::do($data);

        return [
            array_merge($this->getAppend($data), $data),
        ];
    }
}
