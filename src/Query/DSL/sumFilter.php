<?php

namespace nailfor\Elasticsearch\Query\DSL;

class sumFilter extends Filter
{
    protected string $field = 'sum';

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
    }
}
