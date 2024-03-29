<?php

namespace nailfor\Elasticsearch\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Elasticsearch.
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
     * @inheritdoc
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * @inheritdoc
     */
    public function fromDateTime($value)
    {
        if ($this->dateFormat == 'U') {
            if (!is_object($value)) {
                return $value;
            }

            return $value->timestamp;
        }

        return parent::fromDateTime($value);
    }

    /**
     * Get indexSettings if exists.
     */
    public function getIndexSettings(): array
    {
        return $this->indexSettings ?? [];
    }

    /**
     * Get mappingProperties if exists.
     */
    public function getMapping(): array
    {
        return $this->mappingProperties ?? [];
    }

    /**
     * @inheritDoc
     */
    public function qualifyColumn($column)
    {
        return $column;
    }
}
