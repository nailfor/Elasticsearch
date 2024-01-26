<?php

namespace nailfor\Elasticsearch\Query\DSL;

class statsFilter extends Filter
{
    protected $field = 'stats';

    public function __construct($data)
    {
        $this->column = 'field';
        $this->value = $data['field'] ?? $data;
    }
}
