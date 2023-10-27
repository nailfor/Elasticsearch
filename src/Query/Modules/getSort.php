<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getSort extends Module
{
    /**
     * Return sort params
     * @return array
     */
    public function getSort() : array
    {
        $res = [];
        foreach($this->builder->orders ?? [] as $order) {
            $column = $order['column'];
            $res[] = [
                $column => [
                    'order' => $order['direction'],
                ],
            ];
        }
        
        return $res;
    }
}
