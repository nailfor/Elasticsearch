<?php

namespace nailfor\Elasticsearch;

trait GetSetTrait
{
    public $attributes = [];

    public function __get(string $name)
    {
        if (key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
    }

    public function __set(string $name, $val)
    {
        $this->attributes[$name] = $val;
    }
}
