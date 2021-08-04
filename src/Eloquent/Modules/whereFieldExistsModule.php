<?php
namespace nailfor\Elasticsearch\Eloquent\Modules;

class whereFieldExistsModule extends Module
{
    public function handle($fields)
    {
        $this->query->whereFieldExists($fields[0]);
    }    
}
