<?php
namespace nailfor\Elasticsearch;

class ClassIterator
{
    protected $folder;
    protected $skip = [
        '.',
        '..',
    ];

    public function __construct($folder, $skip = '')
    {
        $this->folder = $folder;
        if ($skip) {
            $this->skip[] = $skip;
        }
    }
    
    /**
     * Return file iterator in current folder
     */
    public function handle()
    {
        $dir = __DIR__;
        $namespace = 'nailfor/Elasticsearch'.substr($this->folder, strlen($dir));
        $namespace = str_replace('/', '\\', $namespace);
        
        $files = scandir($this->folder);
        foreach ($files as $file) {
            $info = pathinfo($file);
            $name = $info['filename'] ?? '';
            if (!$name || in_array($name, $this->skip)) {
                continue;
            }
            yield $namespace.'\\'.$name;
        }
    }
}
