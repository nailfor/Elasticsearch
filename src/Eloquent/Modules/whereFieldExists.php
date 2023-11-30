<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class whereFieldExists extends Module
{
    public function handle($fields): Builder
    {
        $this->query->whereFieldExists($fields[0]);

        return $this->builder;
    }
}
