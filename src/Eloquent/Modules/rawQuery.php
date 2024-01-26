<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

/**
 * Set raw query.
 */
class rawQuery extends Module
{
    public function handle($fields): Builder
    {
        $this->query->setRawQuery($fields[0]);

        return $this->builder;
    }
}
