<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;

class suggest extends Module
{
    use Traits\WhereTrait;

    protected array $skip = [];
    
    protected string $type = '';

    protected array $suggest = [];

    public function handle(array $params)
    {
        $name = array_shift($params);
        $params = $params[0] ?? [];

        $wheres = null;
        if ($params instanceof \Closure) {
            $query = $this->newEloquentQuery();
            $newQuery = $params($query);
            $builder = $newQuery->getQuery();
            $wheres = $builder->wheres;
        }

        $filter = $this->getWhereFilter($wheres);
        if (is_array($params)) {
            $filter = array_merge($filter, $params);
        }

        if ($filter) {
            $this->suggest[$name] = $filter[0] ?? [];
        }

        return $this->builder;
    }

    protected function getFilter(string $type, array $where): array
    {
        return FilterFactory::create('suggest', $where);
    }

    public function getBody(): array
    {
        if (!$this->suggest) {
            return [];
        }

        return [
            'suggest' => $this->suggest,
        ];
    }
}
