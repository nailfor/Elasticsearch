<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

class Max extends Stats
{
    public const TYPE = 'max';

    protected function check(string $key): bool
    {
        $type = static::getType();
        $this->alias = $key;

        return strpos($key, $type) === 0;
    }
}
