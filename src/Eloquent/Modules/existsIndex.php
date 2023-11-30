<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class existsIndex extends Module
{
    public function handle($fields)
    {
        return $this->query->existsIndex($fields);
    }
}
