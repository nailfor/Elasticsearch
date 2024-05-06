<?php

namespace nailfor\Elasticsearch\Query\Modules\Traits;

use nailfor\Elasticsearch\Factory\FilterFactory;

trait WhereTrait
{
    /**
     * Return where params.
     */
    protected function getWhereFilter(array $wheres = null): array
    {
        $res = [];
        $wheres = $wheres ?? $this->builder->wheres;
        foreach($wheres ?? [] as $where) {
            $type = $where['type'];
            $operator = $where['operator'] ?? null;
            if (in_array($operator, $this->skip)) {
                continue;
            }
            if ($this->type && $this->type !== $where['boolean']) {
                continue;
            }

            $res[] = $this->getFilter($type, $where);
        }

        return $res;
    }

    protected function getFilter(string $type, array $where): array
    {
        return FilterFactory::create($type, $where);
    }
}
