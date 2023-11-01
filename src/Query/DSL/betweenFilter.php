<?php

namespace nailfor\Elasticsearch\Query\DSL;

class betweenFilter extends Filter
{
    protected $from;
    protected $to;
    protected $field = 'range';
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $val = $data['values'] ?? [];
        
        $this->from = $val[0] ?? 0;
        $this->to = $val[1] ?? 0;
    }
    
    /**
     * @inheritdoc
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
