<?php

namespace nailfor\Elasticsearch\Query\Modules;

class nested extends AbstractNested
{
    public function getMust(): array
    {
        return $this->nested();
    }
}
