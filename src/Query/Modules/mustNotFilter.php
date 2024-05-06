<?php

namespace nailfor\Elasticsearch\Query\Modules;

class mustNotFilter extends Module
{
    use Traits\WhereTrait;

    protected array $skip = [
        null,
        '=',
    ];

    protected string $type = 'and';

    /**
     * Return must params.
     */
    public function getMustNot(): array
    {
        return $this->getWhereFilter();
    }
}
