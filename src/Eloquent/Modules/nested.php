<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

/**
 * Set search query.
 */
class nested extends Module
{
    public function handle($fields): Builder
    {
        $this->query->nested(...$fields);

        return $this->builder;
    }
}
