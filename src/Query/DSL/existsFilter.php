<?php

namespace nailfor\Elasticsearch\Query\DSL;

class existsFilter extends Filter
{
    protected $field = 'exists';

    public function __construct($data)
    {
        $this->value    = $data;
        $this->column   = 'field';
    }
}
