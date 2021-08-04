<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupBySum extends groupByAverage
{
    protected $field = 'sum';
    protected $type = 'sum';
}
