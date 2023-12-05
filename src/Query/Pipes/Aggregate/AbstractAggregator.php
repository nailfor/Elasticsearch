<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

use nailfor\Elasticsearch\Factory\PipeFactory;
use nailfor\Elasticsearch\Query\Pipes\AbstractPipe;

abstract class AbstractAggregator extends AbstractPipe implements AggregatePipeInterface
{
    public const TYPE = '';

    protected const AGG_NAME = '__aggregate_name';

    protected const AGG_PARENT = '__aggregate_parent';

    protected string $alias;

    public static function getAggregate(array $aggregate): array
    {
        $hub = PipeFactory::create(AggregatePipeInterface::class);

        return $hub->pipe($aggregate);
    }

    public static function getType(): string
    {
        return static::TYPE . '_';
    }

    protected function do(array $data): array
    {
        $key = array_key_first($data);
        $type = static::getType();
        $this->alias = substr($key, strlen($type));

        return $data[$key];
    }

    protected function getBucket(array $item): array
    {
        $result = [];
        foreach ($item as $key => $val) {
            if (is_array($val)) {
                $data = static::getAggregate([
                    $key => array_merge([
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
        }

        return $result;
    }

    protected function check(array $data): bool
    {
        $key = array_key_first($data);
        $type = static::getType();

        return strpos($key, $type) === 0;
    }
}
