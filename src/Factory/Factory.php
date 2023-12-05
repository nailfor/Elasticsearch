<?php

declare(strict_types=1);

namespace nailfor\Elasticsearch\Factory;

use Closure;
use Illuminate\Support\Collection;
use nailfor\Elasticsearch\ClassIterator;
use ReflectionClass;

abstract class Factory
{
    abstract public static function create(mixed $params);

    public static function getCollection(string $interface): Collection
    {
        $iterator = static::getIterator($interface);
        $filter = Closure::fromCallable([static::class, 'filter']);

        return collect($iterator->handle())
            ->filter($filter)
        ;
    }

    protected static function getIterator(string $interface): ClassIterator
    {
        return new ClassIterator($interface);
    }

    protected static function filter($item): bool
    {
        $class = new ReflectionClass($item);

        return !$class->isAbstract();
    }
}
