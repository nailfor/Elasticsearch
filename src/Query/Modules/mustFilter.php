<?php
namespace nailfor\Elasticsearch\Query\Modules;

class mustFilter extends Module
{
    protected $skip = [
        '!=',
    ];

    /**
     * Return must params
     * @return array
     */
    public function getMust() : array
    {
        return $this->getWhereFilter();
    }
}
