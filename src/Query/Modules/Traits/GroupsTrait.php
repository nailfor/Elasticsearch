<?php

namespace nailfor\Elasticsearch\Query\Modules\Traits;

trait GroupsTrait
{
    /**
     * Return groups aggregations.
     */
    public function getGroups($groups = []): array
    {
        $field = $this->field;
        $array = $this->builder->groupBy[$field] ?? null;

        if (!is_array($array)) {
            return $groups;
        }

        $prefix = $this->getPrefix();
        foreach($array as $alias => $group) {
            $alias = $prefix . $alias;
            $groups[$alias] = $this->getGroup($group, $alias, $groups[$alias] ?? null);
        }

        return $groups;
    }

    protected function getGroup(array|string $group, string $alias, ?array $merge): array
    {
        return $group;
    }

    protected function getPrefix(): string
    {
        return '';
    }
}
