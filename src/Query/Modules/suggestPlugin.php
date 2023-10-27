<?php

namespace nailfor\Elasticsearch\Query\Modules;

class suggestPlugin extends Module
{
    public function handle(array $items): array
    {
        $model = $items[0] ?? [];
        $results = [];
        if (!$model) {
            return $results;
        }
        $suggests = $model['suggest'] ?? [];
        foreach ($suggests as $key => $suggest) {
            foreach ($suggest as $item) {
                $item['__suggest_name'] = $key;
                $results[] = $item;
            }
        }

        return $results;
    }
}
