<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

/**
 * Set search query.
 */
class orNested extends Module
{
    public function handle($fields): Builder
    {
        $this->query->orNested(...$fields);

        return $this->builder;
    }
}
