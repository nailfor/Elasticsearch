<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getBody extends Module
{
    public function getBody() : array
    {
        $result = [
            'query' => $this->builder->getQueryBody(),
        ];

        $columns = $this->builder->columns;
        if ($columns) {
            $result['_source'] = $columns;
        }

        return $result;
    }
}
