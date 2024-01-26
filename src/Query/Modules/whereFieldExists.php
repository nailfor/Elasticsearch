<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;

class whereFieldExists extends Module
{
    protected $field = 'exists';

    public function handle($params)
    {
        $field = $this->field;
        $data = $this->builder->$field;

        $data[] = $params[0];
        $this->builder->$field = $data;

        return $this->builder;
    }

    public function getMust(): array
    {
        $res = [];
        $field = $this->field;
        $data = $this->builder->$field;
        if ($data) {
            foreach($data as $exists) {
                $res[] = FilterFactory::create('exists', $exists);
            }
        }

        return $res;
    }
}
