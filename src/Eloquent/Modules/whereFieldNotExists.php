<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class whereFieldNotExists extends Module
{
    public function handle($fields): Builder
    {
        $this->query->whereFieldNotExists($fields[0]);

        return $this->builder;
    }
}
