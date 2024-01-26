<?php

namespace nailfor\Elasticsearch\Query\Modules;

class createIndex extends Module
{
    public function handle($params)
    {
        list($settings, $mappingProperties, $shards, $replicas) = $params;

        $client = $this->getClient();

        $index = [
            'index' => $this->builder->from,
        ];

        if ($settings) {
            $index['body']['settings'] = $settings;
        }

        if ($mappingProperties) {
            $index['body']['mappings'] = [
                'properties' => $mappingProperties,
            ];
        }

        if (!is_null($shards)) {
            $index['body']['settings']['number_of_shards'] = $shards;
        }

        if (!is_null($replicas)) {
            $index['body']['settings']['number_of_replicas'] = $replicas;
        }

        return $client->indices()->create($index);
    }
}
