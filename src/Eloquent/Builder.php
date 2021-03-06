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
     * Set search query
     * @param $query
     * @return $this
     */
    public function rawQuery($query)
    {
        $this->query->setRawQuery($query);
        
        return $this;
    }
    
    public function whereFieldExists($field)
    {
        $this->query->whereFieldExists($field);
        
        return $this;
    }    
    
    public function whereFieldNotExists($field)
    {
        $this->query->whereFieldNotExists($field);
        
        return $this;
    }    
    
    
    public function groupByRange(...$groups)
    {
        $this->query->groupByRange($groups);
        
        return $this;
    }
    
    public function groupByInterval(...$groups)
    {
        $this->query->groupByRange($groups, 'interval');
        
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

    /**
     * Create an elasticsearch index
     * @param int $shards
     * @param int $replicas
     * @return type
     */
    public function createIndex(int $shards = null, int $replicas = null) 
    {
        $settings = $this->model->getIndexSettings();
        $mappings = $this->model->getMapping();
        
        return $this->query->createIndex($settings, $mappings, $shards, $replicas);
    }
    
    public function existsIndex() 
    {
        return $this->query->existsIndex();
    }

    public function deleteIndex() 
    {
        return $this->query->deleteIndex();
    }
    
}
