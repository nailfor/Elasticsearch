<?php

namespace nailfor\Elasticsearch\Query\DSL;

class nestedFilter extends Filter
{
    protected string $field = 'nested';

    public function __construct(array|string $data)
    {
        $this->column = 'path';
        $this->value = $data['field'] ?? $data;
    }
}
