<?php

namespace nailfor\Elasticsearch\Query\DSL;

class betweenFilter extends Filter
{
    protected $from;
    protected $to;
    protected $field = 'range';
    
    public function __construct($data, $filter)
    {
        parent::__construct($data, $filter);
        
        $old = [];
        if ($filter) {
            $range = $filter['range'] ?? [];
            $old = $range[$this->column] ?? [];
        }
        
        $val = $data['values'] ?? [];
        $from   = $val[0] ?? 0;
        $to     = $val[1] ?? 0;
        if ($old) {
            $this->from = max($from, $old['gte'] ?? 0);
            $this->to   = max($to, $old['lte'] ?? 0);
            return;
        }
        
        $this->from = $from;
        $this->to = $to;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return [
            //'format' => 'strict_date_optional_time',
            'gte' => $this->from,
            'lte' => $this->to,
        ];
    }
    
}