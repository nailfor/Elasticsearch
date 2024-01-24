<?php

namespace nailfor\Elasticsearch\Query\Pipes;

use Closure;

interface PipeInterface
{
    public function handle(array $data, Closure $next);
}
