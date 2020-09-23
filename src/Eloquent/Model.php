<?php

namespace nailfor\Elasticsearch\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Date;

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
    protected $dateFormat = 'U';
    protected $primaryKey = '_id';

    /**
     * {@inheritdoc}
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }


    /**
     * {@inheritdoc}
     */
    public function fromDateTime($value)
    {
        if ($this->dateFormat == 'U') {
            return $value->timestamp;
        }
        
        return parent::fromDateTime($value);
    }
}
