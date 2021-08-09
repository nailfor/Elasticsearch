<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupByHistogram extends groupByAverage
{
    protected $field = 'histogram';
    protected $type = 'histogram';
}
