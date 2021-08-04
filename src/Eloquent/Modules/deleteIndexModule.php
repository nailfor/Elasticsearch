<?php
namespace nailfor\Elasticsearch\Eloquent\Modules;

class deleteIndexModule extends Module
{
    public function handle($fields)
    {
        return $this->query->deleteIndex();
    }    
}
