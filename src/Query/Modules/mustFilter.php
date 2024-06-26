<?php

namespace nailfor\Elasticsearch\Query\Modules;

class mustFilter extends Module
{
    use Traits\WhereTrait;

    protected array $skip = [
        '!=',
    ];

    protected string $type = 'and';

    /**
     * Return must params.
     */
    public function getMust(): array
    {
        return $this->getWhereFilter();
    }
}
