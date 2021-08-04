<?php
namespace nailfor\Elasticsearch\Query\Modules;

class whereFieldNotExists extends whereFieldExists
{
    protected $field = 'notExists';
}
