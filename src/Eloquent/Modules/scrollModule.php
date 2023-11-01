<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

/**
 * Set search query
 */
class scrollModule extends Module
{
    public function handle($params)
    {
        $this->query->scroll($params[0] ?? null);

        return $this->builder;
    }
}
