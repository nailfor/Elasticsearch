<?php

namespace nailfor\Elasticsearch\Query\Modules;

class must extends Module
{
    protected array $params = [];

    public function handle(array $params)
    {
        $this->builder->query = array_shift($params);
        $this->params = $params[0] ?? [];

        return $this->builder;
    }

    /**
     * Return must params
     * @return array
     */
    public function getMust() : array
    {
        $columns = $this->builder->columns;
        $query = $this->builder->query;

        $res = [];
        if ($query) {
            $match = [
                'fields'=> $columns ? : ['*'],
                'query' => $query,
            ];
            $match = array_merge($match, $this->params);

            if ($columns) {
                $match['operator'] = 'and';
            }
            $res['multi_match'] = $match;
        }
        else {
            $res['match_all'] = (object)[];
        }
        
        return [$res];
    }
}
