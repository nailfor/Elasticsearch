<?php

namespace nailfor\Elasticsearch\Query\Modules;

/**
 * Check exists index
 * @return bool
 */
class existsIndex extends Module
{
    public function handle($params)
    {
        $client = $this->getClient();

        $index = [
            'index' => $this->builder->from,
            'type'  => '_doc',
        ];
        
        return $client->indices()->exists($index)->asBool();
    }
}
