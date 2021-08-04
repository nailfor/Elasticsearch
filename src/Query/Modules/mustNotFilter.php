<?php
namespace nailfor\Elasticsearch\Query\Modules;

class mustNotFilter extends mustFilter
{
    protected $operator = '==';
    protected $field = 'notExists';
}
