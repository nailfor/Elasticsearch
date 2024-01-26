<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

/**
 * Set search query.
 */
class scroll extends Module
{
    public function handle($params): Builder
    {
        $this->query->scroll($params[0] ?? null);

        return $this->builder;
    }
}
