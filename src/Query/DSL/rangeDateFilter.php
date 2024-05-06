<?php

namespace nailfor\Elasticsearch\Query\DSL;

class rangeDateFilter extends Filter
{
    protected string $field = 'date_range';

    protected string $format;

    protected array $ranges;

    public function __construct(array $data)
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
    protected function append(): array
    {
        return [
            'format' => $this->format,
            'ranges' => $this->ranges,
        ];
    }
}
