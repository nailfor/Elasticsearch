<?php

namespace nailfor\Elasticsearch\Query\Modules;

/**
 * Drop index
 * @return type
 */
class deleteIndex extends Module
{
    public function handle()
    {
        $client = $this->getClient();

        $index = [
            'index' => $this->builder->from,
        ];
        
        return $client->indices()->delete($index);
    }
}
