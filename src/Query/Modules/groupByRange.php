<?php

namespace nailfor\Elasticsearch\Query\Modules;

class groupByRange extends ModuleGroup
{
    protected string $field = 'ranges';
    protected string $type = 'range';
}
