<?php

namespace nailfor\Elasticsearch\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Pagination\Paginator;
use nailfor\Elasticsearch\Eloquent\Modules\ModuleInterface;
use nailfor\Elasticsearch\ModuleTrait;
use nailfor\Elasticsearch\Query\QueryBuilder;
use Closure;
use Http\Promise\Promise;

/**
 * Elasticsearch.
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
     * Create an elasticsearch index.
     * @return Promise
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
        $builder = $this->getQuery();
        $newBuilder = $builder->connection->query();
        $queryClone = clone $this;
        $queryClone->setQuery($newBuilder);

        return $queryClone;
    }

    /**
     * @inheritDoc
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);
        $results = $this
            ->forPage($page, $perPage)
            ->get($columns)
        ;
        $total = $this->toBase()->getCount();

        $perPage = $perPage instanceof Closure
            ? $perPage($total)
            : $perPage
        ;
        $perPage = $perPage ?: $this->model->getPerPage();

        return $this->paginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}
