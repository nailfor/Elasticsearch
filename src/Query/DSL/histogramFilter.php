<?php

namespace nailfor\Elasticsearch\Query\DSL;

class histogramFilter extends Filter
{
    protected string $field = 'histogram';

    protected $interval;

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['field'];
        $this->interval = $data['interval'] ?? '10';
    }

    /**
     * Return append for getFilter.
     */
    protected function append(): array
    {
        return [
            'interval' => $this->interval,
        ];
    }
}
