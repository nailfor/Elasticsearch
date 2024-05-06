<?php

namespace nailfor\Elasticsearch\Query\DSL;

class averageFilter extends Filter
{
    protected string $field = 'avg';

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
    }
}
