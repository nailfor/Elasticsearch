<?php

namespace nailfor\Elasticsearch\Query\Modules;

class orNested extends AbstractNested
{
    public function getShould(): array
    {
        return $this->nested();
    }
}
