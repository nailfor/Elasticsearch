<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

/**
 * Print debug request
 */
class dd extends Module
{
    public function handle($fields)
    {
        $params = $this->query->getParams();
        $toJson = $fields[0] ?? 0;
        
        if ($toJson) {
            echo json_encode($params, JSON_PRETTY_PRINT);
            exit;
        }
        
        dd($params);
    }
}
