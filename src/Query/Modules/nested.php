<?php
namespace nailfor\Elasticsearch\Query\Modules;

class nested extends Module
{
    protected string $path = '';

    protected array $query = [];

    public function handle(array $params)
    {
        $this->path = $params[0] ?? '';
        $this->query = $params[1] ?? [];

        return $this->builder;
    }

    /**
     * Return required params
     * @return array
     */
    public function getQueryBody() : array
    {
        $query = [
            'bool' => $this->builder->getBool(),
        ];
        if ($this->path) {
            return [
                'nested' => [
                    'path' => $this->path,
                    'query' => $query,
                ],
            ];
        }

        return $query;
    }
}
