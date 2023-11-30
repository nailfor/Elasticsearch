<?php

namespace nailfor\Elasticsearch\Eloquent;

use nailfor\Elasticsearch\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use nailfor\Elasticsearch\Eloquent\Modules\ModuleInterface;
use nailfor\Elasticsearch\ModuleTrait;
/**
 * Elasticsearch
 *
 */
class Builder extends EloquentBuilder
{
    use ModuleTrait;
    
    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
        $this->init(ModuleInterface::class, [
            'query' => $query,
            'builder' => $this,
        ]);
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

    public function update(array $values)
    {
        $query = $this->query;
        $att = $this->model->getAttributes();
        $key = $this->model->getKeyName();
        $values[$key] = $att[$key] ?? 0;
        
        return $query->update($values);
    }

    /**
     * @inheritDoc
     */
    public function clone()
    {
        $builder    = $this->getQuery();
        $newBuilder = $builder->connection->query();
        $queryClone = clone $this;
        $queryClone->setQuery($newBuilder);
        
        return $queryClone;
    }
}
