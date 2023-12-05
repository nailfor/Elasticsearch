<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Sum;

class groupBySum extends groupByAverage
{
    protected string $field = 'sum';
    protected string $type = 'sum';

    protected function getPrefix(): string
    {
        return Sum::getType();
    }
}
