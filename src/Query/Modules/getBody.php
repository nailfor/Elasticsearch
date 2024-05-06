<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getBody extends Module
{
    public function handle(): array
    {
        $body = [];
        $builder = $this->builder;
        $builder->runModule('getBody', $body, 'body', true);

        return $body['body'] ?? [];
    }

    public function getBody(): array
    {
        $result = [
            'query' => $this->builder->getQueryBody(),
        ];

        $columns = $this->builder->columns;
        if ($columns) {
            $result['_source'] = $columns;
        }

        return $result;
    }
}
