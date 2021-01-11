<?php

namespace nailfor\Elasticsearch\Query\DSL;

class Filter
{
    protected $column;
    protected $value;
    protected $field = 'term';
    
    public function __construct($data)
    {
        $this->column   = $data['column'] ?? '';
        $this->value    = $data['value'] ?? '';
    }
    
    /**
     * Return current filter
     * @return array
     */
    public function getFilter() : array
    {
        $field = $this->field;

        $res = array_merge([
            $this->column => $this->getValue(),
        ], $this->append());

        return [
            $field => $res,
        ];
    }
    
    /**
     * Return append for getFilter
     * @return type
     */
    protected function append()
    {
        return [];
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