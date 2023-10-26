<?php
namespace nailfor\Elasticsearch\Query\Modules;

class mustNotFilter extends Module
{
    protected array $skip = [
        null,
        '=',
    ];
    
    /**
     * Return must params
     * @return array
     */
    public function getMustNot() : array
    {
        return $this->getWhereFilter();
    }
}
