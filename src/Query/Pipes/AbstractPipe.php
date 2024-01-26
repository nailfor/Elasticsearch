<?php

namespace nailfor\Elasticsearch\Query\Pipes;

use Closure;

abstract class AbstractPipe implements PipeInterface
{
    abstract protected function check(string $key): bool;

    abstract protected function do(array $data): array;

    public function handle(array $dto, Closure $next)
    {
        $data = $dto['data'] ?? [];
        foreach ($data as $key => $val) {
            if ($this->check($key)) {
                $aggs = $this->do($val);
                $dto['result'] = array_merge($aggs, $dto['result'] ?? []);
            }
        }

        return $next($dto);
    }
}
