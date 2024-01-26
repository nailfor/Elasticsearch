<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Range;

class groupByRange extends ModuleGroup
{
    protected string $field = 'ranges';

    protected string $type = 'range';

    protected function getPrefix(): string
    {
        return Range::getType();
    }
}
