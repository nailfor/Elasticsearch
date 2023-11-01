<?php
namespace nailfor\Elasticsearch\Query\Modules;

class groupBySum extends groupByAverage
{
    protected string $field = 'sum';
    protected string $type = 'sum';
}
