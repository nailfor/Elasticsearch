<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getSort extends Module
{
    /**
     * Return sort params
     * @return array
     */
    public function getBody() : array
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

        if (!$res) {
            return [];
        }
        
        return [
            'sort' => $res,
        ];
    }
}
