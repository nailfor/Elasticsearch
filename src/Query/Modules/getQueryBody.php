<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getQueryBody extends Module
{
    public function handle(): array
    {
        return $this->getQueryBody();
    }

    /**
     * Return required params
     * @return array
     */
    public function getQueryBody(): array
    {
        $query = [
            'bool' => $this->builder->getBool(),
        ];
        return $query;
    }
}
