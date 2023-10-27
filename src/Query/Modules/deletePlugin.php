<?php

namespace nailfor\Elasticsearch\Query\Modules;

class deletePlugin extends Module
{
    public function handle($params)
    {
        $client = $this->getClient();
        
        $id = $params[0] ?? 0;
        if (!$id) {
            foreach($this->builder->wheres ?? [] as $where) {
                if ($where['column'] == '_id') {
                    $id = $where['value'];
                    break;
                }
            }
            
            if (!$id) {
                return;
            }
        }
        
        $params = [
            'index' => $this->builder->from,
            'id' => $id
        ];
        
        return $client->delete($params);
    }
}
