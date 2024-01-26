<?php

namespace nailfor\Elasticsearch\Query\DSL;

class rangeFilter extends Filter
{
    protected $field = 'range';

    protected $ranges;

    public function __construct($data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
        $this->ranges = $data['ranges'] ?? [];
    }

    /**
     * Return append for getFilter.
     * @return array
     */
    protected function append()
    {
        return [
            'ranges' => $this->ranges,
        ];
    }
}
