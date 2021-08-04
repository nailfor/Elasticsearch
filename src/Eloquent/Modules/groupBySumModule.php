<?php
namespace nailfor\Elasticsearch\Eloquent\Modules;

class groupBySumModule extends Module
{
    public function handle($fields)
    {
        $this->query->groupBySum($fields);
    }    
}
