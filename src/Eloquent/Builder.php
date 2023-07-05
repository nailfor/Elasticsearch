<?php

namespace nailfor\Elasticsearch\Eloquent;

use nailfor\Elasticsearch\Query\QueryBuilder as Query;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use nailfor\Elasticsearch\ModuleTrait;
/**
 * Elasticsearch
 *
 */
class Builder extends EloquentBuilder
{
    use ModuleTrait;
    
    public function __construct(Query $query)
    {
        $this->query = $query;
        $this->init(__DIR__.'/Modules', 'Module', $query);
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
}
