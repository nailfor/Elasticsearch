<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Interval;

class groupByInterval extends groupByRange
{
    protected string $field = 'interval';

    protected string $type = 'interval';

    protected function getPrefix(): string
    {
        return Interval::getType();
    }
}
