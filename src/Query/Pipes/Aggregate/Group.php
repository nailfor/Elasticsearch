<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

class Group extends AbstractAggregator
{
    public const TYPE = 'group';

    protected function do(array $data): array
    {
        $data = parent::do($data);
        $buckets = $data['buckets'] ?? [];

        $append = $this->getAppend($data);
        $result = [];
        foreach ($buckets as $item) {
            $drop = $this->map($item);
            $result[] = array_merge($append, $drop);

            $bucket = $this->getBucket($item);
            $result = array_merge($result, $bucket);

        }

        return $result;
    }

    protected function map(array $item): array
    {
        return [
            'key' => $item['key'] ?? $item,
            'count' => $item['doc_count'] ?? 0,
        ];
    }
}
