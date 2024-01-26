<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

/**
 * Set search query.
 */
class query extends Module
{
    public function handle($fields): Builder
    {
        $this->query->must(...$fields);

        return $this->builder;
    }
}
