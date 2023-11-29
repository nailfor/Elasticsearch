<?php

namespace nailfor\Elasticsearch\Query\Modules;

class nested extends Module
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

    /**
     * Return required params
     * @return array
     */
    public function getQueryBody(): array
    {
        $query = [
            'bool' => $this->builder->getBool(),
        ];
        return $query;
    }

    public function getMust(): array
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
