<?php

namespace nailfor\Elasticsearch\Query\Modules;

class shouldFilter extends Module
{
    use Traits\WhereTrait;

    protected array $skip = [
        '!=',
    ];

    protected string $type = 'or';

    /**
     * Return must params.
     */
    public function getShould(): array
    {
        return $this->getWhereFilter();
    }
}
