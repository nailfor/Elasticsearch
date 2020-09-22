<?php

namespace nailfor\Elasticsearch\Eloquent;

use nailfor\Elasticsearch\Query\QueryBuilder as Query;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Elasticsearch
 *
 */
class Builder extends EloquentBuilder
{
    public function __construct(Query $query)
    {
        $this->query = $query;
    }
    
    /**
     * Set search query
     * @param string $query
     * @return $this
     */
    public function query(string $query)
    {
        $this->query->setQuery($query);
        
        return $this;
    }

    /**
     * Print debug request
     */
    public function dd()
    {
        $query = $this->getQuery();
        dd($query->getParams());
    }
}
