<?php

namespace nailfor\Elasticsearch\Eloquent\Modules;

class deleteIndex extends Module
{
    public function handle($fields)
    {
        return $this->query->deleteIndex();
    }
}
