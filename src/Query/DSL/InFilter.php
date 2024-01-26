<?php

namespace nailfor\Elasticsearch\Query\DSL;

class InFilter extends Filter
{
    protected $column;

    protected $value;

    protected $field = 'terms';

    public function __construct($data)
    {
        $this->column = $data['column'] ?? '';
        $this->value = $data['values'] ?? '';
    }
}
