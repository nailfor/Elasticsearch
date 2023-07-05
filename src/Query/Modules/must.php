<?php
namespace nailfor\Elasticsearch\Query\Modules;

class must extends Module
{
    protected $builder;

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
            $match = array_merge($match, $this->builder->params ?? []);

            if ($columns) {
                $match['operator'] = 'and';
            }
            $res['multi_match'] = $match;
        }
        else {
            $res['match_all'] = (object)[];
        }
        
        return $res;
    }
}
