<?php

namespace nailfor\Elasticsearch\Query\DSL;

class Filter
{
    protected $column;
    protected $value;
    protected $filter;
    protected $field = 'term';
    
    public function __construct($data, $filter)
    {
        $this->column   = $data['column'] ?? '';
        $this->value    = $data['value'] ?? '';
        $this->filter   = $filter;
    }
    
    /**
     * Return current filter
     * @return array
     */
    public function getFilter() : array
    {
        $field = $this->field;

        $res = [
            $this->column => $this->getValue(),
        ];
        $old = $this->filter[$field] ?? [];

        return [
            $field => array_merge($old, $res),
        ];
    }
    
    /**
     * Get value
     * @return type
     */
    protected function getValue()
    {
        return $this->value;
    }
}