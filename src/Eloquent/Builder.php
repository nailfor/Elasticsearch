<?php

namespace nailfor\Elasticsearch\Eloquent;

use nailfor\Elasticsearch\Query\QueryBuilder as Query;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;

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
    
    public function query($query)
    {
        $this->query->setQuery($query);
        
        return $this;
    }

}
