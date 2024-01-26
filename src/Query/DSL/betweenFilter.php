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

        $this->from = $val[0] ?? null;
        $this->to = $val[1] ?? null;
    }

    /**
     * @inheritdoc
     */
    protected function getValue()
    {
        $result = [];
        if ($this->from) {
            $result['gte'] = $this->from;
        }
        if ($this->to) {
            $result['lte'] = $this->to;
        }

        return $result;
    }
}
