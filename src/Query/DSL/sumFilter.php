<?php

namespace nailfor\Elasticsearch\Query\DSL;

class sumFilter extends Filter
{
    protected $field = 'sum';
    
    public function __construct($data)
    {
        $this->column   = 'field';
        $this->value    = $data['field'];
    }
}
