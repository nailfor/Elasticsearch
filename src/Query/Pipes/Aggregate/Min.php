<?php

namespace nailfor\Elasticsearch\Query\Pipes\Aggregate;

class Min extends Stats
{
    public const TYPE = 'min';

    protected function check(string $key): bool
    {
        $type = static::getType();
        $this->alias = $key;

        return strpos($key, $type) === 0;
    }
}
