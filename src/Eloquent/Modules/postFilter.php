<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

use nailfor\Elasticsearch\Eloquent\Builder;

class postFilter extends Module
{
    public function handle($fields): Builder
    {
        $this->query->postFilter(...$fields);

        return $this->builder;
    }
}
