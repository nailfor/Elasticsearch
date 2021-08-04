<?php
namespace nailfor\Elasticsearch\Query\Modules;

class whereFieldExists extends Module
{
    protected $field = 'exists';
    

    public function handle($params)
    {
        $field = $this->field;
        $data = $this->builder->$field;
        
        $data[] = $params[0];
        $this->builder->$field = $data;
    }
}
