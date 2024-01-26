<?php

namespace nailfor\Elasticsearch\Query\Modules;

class mustFilter extends Module
{
    protected array $skip = [
        '!=',
    ];

    /**
     * Return must params.
     */
    public function getMust(): array
    {
        return $this->getWhereFilter();
    }
}
