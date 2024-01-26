<?php

namespace nailfor\Elasticsearch\Query\Modules;

class postFilter extends Module
{
    protected array $filter = [];

    public function handle(array $params): void
    {
        $closure = $params[0] ?? [];
        $builder = $this->builder;
        if ($closure instanceof \Closure) {
            $query = $this->newEloquentQuery();
            $query = $closure($query);
            $builder = $query->getQuery();
        }
        $bool = $builder->getBool();
        $must = $bool['must'] ?? [];
        foreach($must as $filter) {
            if (!is_array($filter)) {
                continue;
            }

            foreach($filter as $key => $val) {
                if ($key == 'match_all') {
                    continue 2;
                }
            }

            $this->filter[] = $filter;
        }
    }

    public function getBody(): array
    {
        if (!$this->filter) {
            return [];
        }

        return [
            'post_filter' => [
                'bool' => [
                    'must' => $this->filter,
                ],
            ],
        ];
    }
}
