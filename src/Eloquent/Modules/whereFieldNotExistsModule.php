<?php
namespace nailfor\Elasticsearch\Eloquent\Modules;

class whereFieldNotExistsModule extends Module
{
    public function handle($fields)
    {
        $this->query->whereFieldNotExists($fields[0]);
    }    
}
