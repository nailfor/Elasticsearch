<?php

namespace nailfor\Elasticsearch\Query\DSL;

class termsFilter extends Filter
{
    protected $field = 'terms';
    protected $limit = 0;


    public function __construct($data)
    {
        $this->column   = 'field';
        $this->value    = $data['value'] ?? $data[0] ?? $data;
        $this->limit    = $data['limit'] ?? $data[1] ?? 0;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function append()
    {
        if (!$this->limit) {
            return [];
        }
        
        return [
            'size' => $this->limit,
        ];
    }
}
