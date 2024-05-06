<?php

namespace nailfor\Elasticsearch\Factory;

use nailfor\Elasticsearch\Query\DSL\Filter;

class FilterFactory
{
    public static function create(string $type, mixed $params)
    {
        $class = "nailfor\\Elasticsearch\\Query\\DSL\\{$type}Filter";

        if (!class_exists($class)) {
            $class = Filter::class;
        }
        $f = new $class($params);

        return $f->getFilter();
    }
}
