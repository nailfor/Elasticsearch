<?php

namespace nailfor\Elasticsearch\Query\DSL;

class statsFilter extends Filter
{
    protected string $field = 'stats';

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'] ?? $data;
    }
}
