<?php

namespace nailfor\Elasticsearch\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Elasticsearch
 *
 */
class Model extends BaseModel
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'elasticsearch';

    /**
     * {@inheritdoc}
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}
