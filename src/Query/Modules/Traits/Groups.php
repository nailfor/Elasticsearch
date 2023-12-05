<?php

namespace nailfor\Elasticsearch\Query\Modules\Traits;

trait Groups
{
    /**
     * Return groups aggregations
     * @return array
     */
    public function getGroups($groups = []) : array
    {
        $field = $this->field;
        $array = $this->builder->groupBy[$field] ?? null;

        if (!is_array($array)) {
            return $groups;
        }
        
        $preffix = $this->getPrefix();
        foreach($array as $alias => $group) {
            $alias = $preffix . $alias;
            $groups[$alias] = $this->getGroup($group, $alias, $groups[$alias] ?? 0);
        }

        return $groups;
    }

    protected function getGroup($group, $alias, $merge) : array
    {
        return $group;
    }

    protected function getPrefix(): string
    {
        return '';
    }
}
