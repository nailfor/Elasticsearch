<?php

namespace nailfor\Elasticsearch\Query\Pipes;

use Closure;

abstract class AbstractPipe implements PipeInterface
{
    abstract protected function check(array $data): bool;
    abstract protected function do(array $data): array;

    public function handle(array $data, Closure $next)
    {
        if ($this->check($data)) {
            return $this->do($data);
        }

        return $next($data);
    }

}
