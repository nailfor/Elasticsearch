<?php

namespace nailfor\Elasticsearch;

use ReflectionClass;

class ClassIterator
{
    public const NAMESPACE = 'nailfor\Elasticsearch';

    protected array $skip = [
        '.',
        '..',
    ];

    protected string $interface = '';

    public function __construct(string $interface)
    {
        $this->skip[] = $interface;

        $this->interface = $interface;
    }

    /**
     * Возвращает итератор файлов в текущем каталоге.
     */
    public function handle()
    {
        $dir = $this->getDir();
        $baseDir = $this->getBaseDir();
        $folder = $baseDir . $dir;

        $namespace = $this->getNamespace();

        $files = scandir($folder);
        foreach ($files as $file) {
            $info = pathinfo($file);
            $name = $info['filename'] ?? '';
            if (!$name || in_array($name, $this->skip)) {
                continue;
            }

            $class = $namespace . '\\' . $name;
            if (!$this->check($class)) {
                continue;
            }

            yield $name => $class;
        }
    }

    protected function getDir(): string
    {
        $dir = str_replace(static::NAMESPACE, '', $this->getNamespace());

        return str_replace('\\', '/', $dir);
    }

    protected function getNamespace(): string
    {
        return substr($this->interface, 0, strrpos($this->interface, '\\'));
    }

    protected function getBaseDir(): string
    {
        return __DIR__;
    }

    protected function check(string $class): bool
    {
        $exists = class_exists($class);
        if (!$exists) {
            return false;
        }

        if ($this->interface && !is_subclass_of($class, $this->interface)) {
            return false;
        }

        $reflect = new ReflectionClass($class);

        return !$reflect->isAbstract();
    }
}
