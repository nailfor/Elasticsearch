<?php
namespace nailfor\Elasticsearch;

trait ModuleTrait
{
    protected $modules = [];
    
    public function __call($method, $parameters)
    {
        $module = $this->modules[$method] ?? '';
        if ($module && method_exists($module, 'handle')) {
            $res = $module->handle($parameters);
            if ($res) {
                return $res;
            }
            
            return $this;
        }
        
        return parent::__call($method, $parameters);
    }
    
    protected function init($folder, $skip, $param)
    {
        $iterator = new ClassIterator($folder, $skip);
        foreach ($iterator->handle() as $class) {
            $pos = strripos($class, '\\');
            $name = substr($class, $pos+1);
            $method = str_replace($skip, '', $name);
            
            
            $this->modules[$method] = new $class($param);
        }
    }
    
    protected function getModules($method)
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
