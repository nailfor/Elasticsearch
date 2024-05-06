<?php

namespace nailfor\Elasticsearch\Query\DSL;

class rangeFilter extends Filter
{
    protected string $field = 'range';

    protected array $ranges;

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
        $this->ranges = $data['ranges'] ?? [];
    }

    /**
     * Return append for getFilter.
     * @return array
     */
    protected function append(): array
    {
        return [
            'ranges' => $this->ranges,
        ];
    }
}
