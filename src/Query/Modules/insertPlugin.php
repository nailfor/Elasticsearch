<?php

namespace nailfor\Elasticsearch\Query\Modules;

class insertPlugin extends Module
{
    public function handle(array $values)
    {
        $values = reset($values);
        // Since every insert gets treated like a batch insert, we will make sure the
        // bindings are structured in a way that is convenient when building these
        // inserts statements by verifying these elements are actually an array.
        if (empty($values)) {
            return true;
        }

        if (! is_array(reset($values))) {
            $values = [$values];
        }

        $body = [];
        foreach ($values as $val) {
            $body[] = [
                'index' => [
                    '_index' => $this->builder->from, 
                    '_id' => $val['_id'] ?? uniqid(),
                ],
            ];
            unset($val['_id']);
            $body[] = $val;
        }
        $params = [
            'body' => $body,
        ];

        $client = $this->getClient();

        return $client->bulk($params);
    }
}
