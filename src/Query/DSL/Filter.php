<?php

namespace nailfor\Elasticsearch\Query\DSL;

class Filter
{
    protected $column;

    protected $value;

    protected $operator;

    protected $field = 'term';

    public function __construct($data)
    {
        $this->column = $data['column'] ?? '';
        $this->value = $data['value'] ?? '';
        $this->operator = $data['operator'] ?? '=';
    }

    /**
     * Return current filter.
     */
    public function getFilter(): array
    {
        $field = $this->field;

        $res = array_merge([
            $this->column => $this->getValue(),
        ], $this->append());

        return [
            $field => $res,
        ];
    }

    /**
     * Return append for getFilter.
     * @return array
     */
    protected function append()
    {
        return [];
    }

    /**
     * Get value.
     * @return mixed
     */
    protected function getValue()
    {
        return $this->value;
    }
}
