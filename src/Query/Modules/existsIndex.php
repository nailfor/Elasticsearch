<?php
namespace nailfor\Elasticsearch\Query\Modules;

/**
 * Check exists index
 * @return type
 */
class existsIndex extends Module
{
    public function handle($params)
    {
        $client = $this->builder->connection->getClient();

        $index = [
            'index' => $this->builder->from,
            'type'  => '_doc',
        ];
        
        return $client->indices()->existsType($index);
    }
}
