<?php

namespace nailfor\Elasticsearch\Query\DSL;

class nestedFilter extends Filter
{
    protected $field = 'nested';

    public function __construct($data)
    {
        $this->column = 'path';
        $this->value = $data['field'] ?? $data;
    }
}
