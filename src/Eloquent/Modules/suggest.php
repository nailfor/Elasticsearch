<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

/**
 * Set search query
 */
class suggest extends Module
{
    public function handle($fields): Builder
    {
        $this->query->suggest(...$fields);

        return $this->builder;
    }
}
