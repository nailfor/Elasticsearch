<?php
namespace nailfor\Elasticsearch\Eloquent\Modules;

class existsIndexModule extends Module
{
    public function handle($fields)
    {
        return $this->query->existsIndex();
    }    
}
