<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

/**
 * Set search query
 */
class nestedModule extends Module
{
    public function handle($fields)
    {
        $this->query->nested(...$fields);

        return $this->builder;
    }
}
