<?php

namespace nailfor\Elasticsearch\Query\DSL;

class averageFilter extends Filter
{
    protected $field = 'avg';
    
    public function __construct($data)
    {
        $this->column   = 'field';
        $this->value    = $data['field'];
    }
}
