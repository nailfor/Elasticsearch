<?php

namespace nailfor\Elasticsearch;

trait ModuleTrait
{
    public $modules = [];

    public function __call($method, $parameters)
    {
        $module = $this->modules[$method] ?? '';
        if ($module && method_exists($module, 'handle')) {
            return $module->handle($parameters);
        }

        return parent::__call($method, $parameters);
    }

    protected function init(string $interface, mixed $param)
    {
        $iterator = new ClassIterator($interface);
        foreach ($iterator->handle() as $method => $class) {
            $this->modules[$method] = new $class($param);
        }
    }

    protected function getModules($method): array
    {
        $res = [];
        foreach($this->modules as $module) {
            if (method_exists($module, $method)) {
                $res[] = $module;
            }
        }

        return $res;
    }
}
