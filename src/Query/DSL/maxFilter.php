<?php

namespace nailfor\Elasticsearch\Query\DSL;

class maxFilter extends Filter
{
    protected string $field = 'max';

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
    }
}
