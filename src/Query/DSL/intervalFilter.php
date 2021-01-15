<?php

namespace nailfor\Elasticsearch\Query\DSL;

class intervalFilter extends Filter
{
    protected $field = 'date_histogram';
    protected $interval;

    public function __construct($data)
    {
        $this->column   = 'field';
        $this->value    = $data['field'];
        $this->interval   = $data['interval'] ?? 'day';
    }
    
    /**
     * Return append for getFilter
     * @return type
     */
    protected function append()
    {
        return [
            'interval' => $this->interval,
        ];
    }    
}