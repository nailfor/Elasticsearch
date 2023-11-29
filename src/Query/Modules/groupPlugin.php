<?php

namespace nailfor\Elasticsearch\Query\Modules;

class groupPlugin extends Module
{
    protected array $groups;

    public function handle(array $groups): array
    {
        $key = '';
        foreach ($groups as $data) {
            $val = $this->isString($data, $key);
            if (!$val) {
                $val = $this->isArray($data, $key);
            }
            if (!$val) {
                $val = $this->isClosure($data, $key);
            }

            $this->groups[$key] = $val;
        }

        return $this->groups;
    }

    protected function isString(mixed $string, &$key): ?string
    {
        if (!is_string($string)) {
            return null;
        }
        $key = $string;

        return $string;
    }

    protected function isArray(mixed $array, &$key): mixed
    {
        if (!is_array($array)) {
            return null;
        }

        if (!$key) {
            $key = array_key_first($array);

            return $array[$key];
        }

        $field = $this->groups[$key] ?? '';

        return [
            'field' => $field,
            'aggs' => $array,
        ];
    }

    protected function isClosure(mixed $closure, &$key): mixed
    {
        if (!is_callable($closure)) {
            return null;
        }

        $query = $this->newEloquentQuery();
        $query = $closure($query);
        $builder = $query->getQuery();

        return $this->isArray($builder->groups, $key);
    }
}
