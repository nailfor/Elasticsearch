<?php

namespace nailfor\Elasticsearch\Query\DSL;

class termsFilter extends Filter
{
    protected string $field = 'terms';

    protected int $limit = 0;

    public function __construct(array $data)
    {
        $this->column = 'field';
        $this->value = $data['value'] ?? $data[0] ?? $data;
        $this->limit = $data['limit'] ?? $data[1] ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function append(): array
    {
        if (!$this->limit) {
            return [];
        }

        return [
            'size' => $this->limit,
        ];
    }
}
