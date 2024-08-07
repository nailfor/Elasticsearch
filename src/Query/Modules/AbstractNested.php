<?php

namespace nailfor\Elasticsearch\Query\Modules;

class AbstractNested extends Module
{
    protected array $body = [];

    protected array $path = [];

    public function handle(array $params)
    {
        $path = array_shift($params);

        $body = [];
        $closure = $params[0] ?? [];
        $builder = $this->builder;
        if ($closure instanceof \Closure) {
            $query = $this->newEloquentQuery();
            $query = $closure($query);
            $builder = $query->getQuery();
        }
        $body = $builder->getBody();
        $builder->wheres = [];
        $this->body[] = $body;
        $this->path[] = $path;

        return $this->builder;
    }

    protected function nested(): array
    {
        $result = [];
        foreach ($this->body as $key => $body) {
            $path = $this->path[$key];
            $result[] = [
                'nested' => array_merge([
                    'path' => $path,
                ], $body),
            ];
        }

        return $result;
    }
}
