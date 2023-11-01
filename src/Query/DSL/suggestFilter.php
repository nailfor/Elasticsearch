<?php

namespace nailfor\Elasticsearch\Query\DSL;

class suggestFilter extends Filter
{
    /**
     * @inheritDoc
     */
    public function getFilter() : array
    {
        $field = $this->field;

        $res = array_merge([
            'field' => $this->column,
        ], $this->append());

        return [
            'text' => $this->getValue(),
            $field => $res,
        ];
    }
}
