<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Max;

class groupByMax extends groupByAverage
{
    protected string $field = 'max';

    protected string $type = 'max';

    protected function getPrefix(): string
    {
        return Max::getType();
    }
}
