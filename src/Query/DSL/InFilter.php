<?php

namespace nailfor\Elasticsearch\Query\DSL;

class InFilter extends Filter
{
    protected string $field = 'terms';

    public function __construct(array $data)
    {
        $this->column = $data['column'] ?? '';
        $this->value = $data['values'] ?? '';
    }
}
