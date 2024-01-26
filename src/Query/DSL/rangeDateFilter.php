<?php

namespace nailfor\Elasticsearch\Query\DSL;

class rangeDateFilter extends Filter
{
    protected $field = 'date_range';

    protected $format;

    protected $ranges;

    public function __construct($data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
        $this->format = $data['format'] ?? 'yyyy-MM-dd';
        $this->ranges = $data['ranges'] ?? [];
    }

    /**
     * Return append for getFilter.
     * @return array
     */
    protected function append()
    {
        return [
            'format' => $this->format,
            'ranges' => $this->ranges,
        ];
    }
}
