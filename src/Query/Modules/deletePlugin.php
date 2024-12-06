<?php

namespace nailfor\Elasticsearch\Query\Modules;

class deletePlugin extends Module
{
    public function handle($params): mixed
    {
        $id = $params[0] ?? 0;
        if ($id) {
            return $this->delete($id);
        }

        return $this->deleteByParams();
    }

    protected function delete(string $id): mixed
    {
        $params = [
            'index' => $this->builder->from,
            'id' => $id,
        ];
        $client = $this->getClient();

        return $client->delete($params);
    }

    protected function deleteByParams(): mixed
    {
        $client = $this->getClient();
        $params = $this->builder->getParams();

        return $client->deleteByQuery($params);
    }
}
