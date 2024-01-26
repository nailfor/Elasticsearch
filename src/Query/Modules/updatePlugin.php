<?php

namespace nailfor\Elasticsearch\Query\Modules;

class updatePlugin extends Module
{
    public function handle(array $params)
    {
        $values = $params[0] ?? [];
        $client = $this->getClient();

        $id = $values['_id'] ?? 0;
        unset($values['_id']);
        $params = [
            'index' => $this->builder->from,
            'body' => [
                'doc' => $values,
            ],
        ];

        if ($id) {
            $params['id'] = $id;
        }

        $res = $client->update($params);

        return $res['_id'] ?? false;
    }
}
