<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

/**
 * Set search query
 */
class suggestModule extends Module
{
    public function handle($fields)
    {
        $this->query->suggest(...$fields);

        return $this->builder;
    }
}
