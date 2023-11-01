<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

/**
 * Set search query
 */
class queryModule extends Module
{
    public function handle($fields)
    {
        $this->query->must($fields);

        return $this->builder;
    }
}
