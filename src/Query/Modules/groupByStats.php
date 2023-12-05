<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Query\Pipes\Aggregate\Stats;

class groupByStats extends ModuleGroup
{
    protected string $field = 'stats';
    protected string $type = 'stats';

    protected function getPrefix(): string
    {
        return Stats::getType();
    }
}
