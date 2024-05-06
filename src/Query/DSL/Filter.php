<?php

namespace nailfor\Elasticsearch\Query\DSL;

class Filter
{
    protected string $column;

    protected mixed $value;

    protected string $operator;

    protected string $field = 'term';

    public function __construct(array $data)
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
    protected function append(): array
    {
        return [];
    }

    /**
     * Get value.
     * @return mixed
     */
    protected function getValue(): mixed
    {
        return $this->value;
    }
}
