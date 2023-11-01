<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

/**
 * Set raw query
 */
class rawQueryModule extends Module
{
    public function handle($fields)
    {
        $this->query->setRawQuery($fields[0]);

        return $this->builder;
    }
}
