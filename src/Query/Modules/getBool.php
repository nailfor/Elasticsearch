<?php

namespace nailfor\Elasticsearch\Query\Modules;

class getBool extends Module
{
    public function handle(): array
    {
        $bool = [];
        $builder = $this->builder;
        $builder->runModule('getMust', $bool, 'must', true);
        $builder->runModule('getMustNot', $bool, 'mustNot', true);
        $builder->runModule('getShould', $bool, 'should', true);

        return $bool;
    }
}
