<?php

namespace nailfor\Elasticsearch\Query\Modules;

class aggregatePlugin extends Module
{
    public function handle(array $params): array
    {
        $response = $params[0] ?? [];
        $aggs = $response['aggregations'] ?? [];
        $res = [];
        foreach($aggs as $agg => $items) {
            $item = $this->getBuckets($items, $agg);
            $res = array_merge($res, $item);
        }
        
        return $res;
    }

    /**
     * Return bucket of loads
     * @param array $items
     * @param string $agg
     * @param array $append
     * @return array
     */
    protected function getBuckets(array $items, string $agg, array $append = []): array
    {
        $append['__aggregate_name'] = $agg;

        $buckets = $items['buckets'] ?? [];
        if (!$buckets && ($items['doc_count'] ?? 0)) {
            [$key, $item] = $this->getLast($items);

            return $this->getBuckets($item, $key);
        }

        if (!$buckets) {
            foreach ($items as $item) {
                $buckets = $item['buckets'] ?? [];
                if ($buckets) {
                    break;
                }
            }
        }

        if (!$buckets) {
            return [
                array_merge($append, $items)
            ];
        }

        return $this->deepBucket($buckets, $agg, $append);
    }

    protected function deepBucket(array $buckets, string $agg, array $append): array
    {
        $res = [];
        foreach ($buckets as $item) {
            $itBucket = 0;
            foreach ($item as $key => $val) {
                $bck = $val['buckets'] ?? 0;
                $v = $val['value'] ?? 0;
                if (is_array($val) && ($bck || $v)) {
                    $app = array_merge($append, [$agg => $item['key']]);
                    $bucket = $this->getBuckets($val, $key, $app);
                    $res = array_merge($res, $bucket);
                    $itBucket = 1;
                }
            }
            
            if ($itBucket) {
                continue;
            }

            [$key, $it] = $this->getLast($item);
            if (is_array($it)) {
                $res[] = array_merge(['__aggregate_name' => $key], $it);
                continue;
            }

            $res[] = array_merge($append, [
                $agg    => $item['key'] ?? $item,
                'count' => $item['doc_count'] ?? $item['count'] ?? 0,
            ]);
        }

        return $res;
    }

    protected function getLast(array $data): array
    {
        $keys = array_keys($data);
        $key = end($keys);

        return [
            $key,
            $data[$key],
        ];
    }
}
