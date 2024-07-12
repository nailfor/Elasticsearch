<?php

namespace nailfor\Elasticsearch\Query\DSL;

class minFilter extends Filter
{
    protected string $field = 'min';

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
    }
}
