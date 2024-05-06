<?php

namespace nailfor\Elasticsearch\Query\DSL;

class existsFilter extends Filter
{
    protected string $field = 'exists';

    public function __construct(mixed $data)
    {
        $this->value = $data;
        $this->column = 'field';
    }
}
