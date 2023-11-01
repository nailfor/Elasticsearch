<?php

namespace nailfor\Elasticsearch\Query\Modules;

class scroll extends Module
{
    protected array $scroll = [];

    public function handle(array $params): void
    {
        $this->scroll = $params[0] ?? [];
    }

    public function getScroll(): array
    {
        return $this->scroll;
    }

    public function scroll($scroll): array
    {
        $client = $this->getClient();

        $res = $client->scroll($scroll);

        return $this->builder->hitsPlugin($res);
    }
}
