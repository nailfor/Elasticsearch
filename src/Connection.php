<?php

namespace nailfor\Elasticsearch;

use nailfor\Elasticsearch\Query\QueryBuilder;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Connection as BaseConnection;

class Connection extends BaseConnection
{
    protected $client;
    
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
        
        $this->client = ClientBuilder::fromConfig($config['config']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function query()
    {
        return new QueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
    
    /**
     * Return Elasticsearch client
     * @return type
     */
    public function getClient()
    {
        return $this->client;
    }
}
