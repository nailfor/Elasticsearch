<?php

namespace nailfor\Elasticsearch\Query\DSL;

class intervalFilter extends Filter
{
    protected $field = 'date_histogram';
    protected $interval;
    protected $zone;

    public function __construct($data)
    {
        $this->column   = 'field';
        $this->value    = $data['field'];
        $this->interval = $data['interval'] ?? 'day';
        $this->zone     = $data['zone'] ?? date_default_timezone_get();
    }
    
    /**
     * Return append for getFilter
     * @return array
     */
    protected function append()
    {
        return [
            'interval' => $this->interval,
            'time_zone' => $this->zone,
        ];
    }
}
