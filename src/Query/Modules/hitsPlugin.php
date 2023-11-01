<?php

namespace nailfor\Elasticsearch\Query\Modules;

class hitsPlugin extends Module
{
    public function handle(array $params): array
    {
        $data = $params[0] ?? [];
        $hits = $data['hits']['hits'] ?? [];

        return array_map(function ($item) {
            $data = $item['_source'];
            $data['_id'] = $item['_id'];

            return $data;
        }, $hits);
    }
}
