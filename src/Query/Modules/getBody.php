<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getBody extends Module
{
    /**
     * Return sort params
     * @return array
     */
    public function getBody() : array
    {
        return [
            'query' => $this->builder->getQueryBody(),
        ];
    }
}
