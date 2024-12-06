<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getSort extends Module
{
    /**
     * Return sort params.
     */
    public function getBody(): array
    {
        $res = [];
        foreach($this->builder->orders ?? [] as $order) {
            $column = $order['column'];
            $params = $order['params'] ?? null;
            if ($params) {
                $res[] = [
                    $column => $params,
                ];
                continue;
            }

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
