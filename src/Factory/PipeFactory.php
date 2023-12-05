<?php

declare(strict_types=1);

namespace nailfor\Elasticsearch\Factory;

use Closure;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Pipeline\Hub;

class PipeFactory extends Factory
{
    protected static string $interface = '';

    public static function create(mixed $interface): Hub
    {
        static::$interface = $interface;

        return static::getHub();
    }

    protected static function getHub(): Hub
    {
        /**@var Hub $hub */
        $hub     = app(Hub::class);
        $closure = Closure::fromCallable([static::class, 'registry']);
        $hub->defaults($closure);

        return $hub;
    }

    protected static function registry(Pipeline $pipeline, mixed $data): mixed
    {
        $pipes = static::getCollection(static::$interface)->toArray();

        return $pipeline
            ->send($data)
            ->through($pipes)
            ->thenReturn();
    }
}
