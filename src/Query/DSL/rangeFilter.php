<?php

namespace nailfor\Elasticsearch\Query\DSL;

class rangeFilter extends Filter
{
    protected $field = 'date_histogram';///'date_range';
    protected $format;

    public function __construct($data)
    {
        $this->column   = 'field';
        $this->value    = $data['field'];
        $this->format   = $data['format'] ?? 'yyyy-m-d';
        $this->ranges   = $data['ranges'] ?? [];
    }
    
    /**
     * Return append for getFilter
     * @return type
     */
    protected function append()
    {
        return [
            'interval' => 'hour',
        ];
        
        return [
            'format' => $this->format,
            'ranges' => $this->ranges,
        ];
    }    
}