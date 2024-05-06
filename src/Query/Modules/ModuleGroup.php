<?php

namespace nailfor\Elasticsearch\Query\Modules;

use nailfor\Elasticsearch\Factory\FilterFactory;

abstract class ModuleGroup extends Module
{
    use Traits\GroupsTrait;

    protected string $field;

    protected string $type;

    public function handle($params)
    {
        $groups = $params[0];

        $data = $groups[0] ?? '';
        $params = $groups[1] ?? [];

        if (is_string($data)) {
            $data = [
                $data => $data,
            ];
        }

        if (!is_array($data)) {
            return $this->builder;
        }

        foreach($data as $group => $field) {
            $this->setField($group, $field, $params);
        }

        return $this->builder;
    }

    protected function setField($group, $field, $params)
    {
        $fieldName = $this->field;

        $data = $this->builder->groupBy ?? [];
        $data[$fieldName][$this->getPrefix() . $group] = $this->getData($field, $params);
        $this->builder->groupBy = $data;
    }

    protected function getData(string $field, mixed $params): mixed
    {
        $data = array_merge([
            'field' => $field,
        ], $params);

        return FilterFactory::create($this->type, $data);
    }
}
