<?php

namespace nailfor\Elasticsearch\Query\Modules;

class groupByInterval extends groupByRange
{
    protected string $type = 'interval';
}
