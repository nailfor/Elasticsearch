<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

use nailfor\Elasticsearch\Factory\AggregateFactory;
use nailfor\Elasticsearch\Query\Pipes\AbstractPipe;

abstract class AbstractAggregator extends AbstractPipe implements AggregatePipeInterface
{
    public const TYPE = '';

    protected const AGG_NAME = '__aggregate_name';

    protected const AGG_PARENT_TYPE = '__aggregate_parent_type';

    protected const AGG_PARENT = '__aggregate_parent';

    protected string $alias;

    public static function getType(): string
    {
        return static::TYPE . '_';
    }

    protected function do(array $data): array
    {
        return $data;
    }

    protected function getBucket(array $item): array
    {
        $result = [];
        foreach ($item as $key => $val) {
            if (is_array($val)) {
                $data = AggregateFactory::handle([
                    $key => array_merge([
                        static::AGG_PARENT_TYPE => $this->alias,
                        static::AGG_PARENT => $item['key'] ?? null,
                    ], $val),
                ]);

                $result = array_merge($result, $data);
            }
        }

        return $result;
    }

    protected function getAppend(array $data): array
    {
        $result = [
            static::AGG_NAME => $this->alias,
        ];

        $parent = $data[static::AGG_PARENT] ?? null;
        if ($parent) {
            $result[static::AGG_PARENT] = $parent;
            $result[static::AGG_PARENT_TYPE] = $data[static::AGG_PARENT_TYPE] ?? null;
        }

        return $result;
    }

    protected function check(string $key): bool
    {
        $type = static::getType();
        $this->alias = substr($key, strlen($type));

        return strpos($key, $type) === 0;
    }
}
