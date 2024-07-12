<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Min;

class groupByMin extends groupByAverage
{
    protected string $field = 'min';

    protected string $type = 'min';

    protected function getPrefix(): string
    {
        return Min::getType();
    }
}
