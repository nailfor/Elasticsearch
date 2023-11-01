<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupByHistogram extends groupByAverage
{
    protected string $field = 'histogram';
    protected string $type = 'histogram';
}
