<?php

namespace nailfor\Elasticsearch;

trait ModuleTrait
{
    public array $modules = [];

    public function __call($method, $parameters)
    {
        $module = $this->modules[$method] ?? '';
        if ($module && method_exists($module, 'handle')) {
            return $module->handle($parameters);
        }

        return parent::__call($method, $parameters);
    }

    public function runModule(string $name, array &$body, ?string $field, bool $add = false): void
    {
        $res = [];
        $modules = $this->getModules($name);
        foreach ($modules as $module) {
            $res = $module->$name($res);
            if ($add && $res) {
                if ($field) {
                    $body[$field] = array_merge($body[$field] ?? [], $res);
                } else {
                    $body[] = $res;
                }
            }
        }

        if ($res && !$add) {
            if ($field) {
                $body[$field] = $res;
            } else {
                $body = $res;
            }
        }
    }     

    protected function init(string $interface, mixed $param): void
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
