<?php

namespace nailfor\Elasticsearch\Query\Modules;

class aggregatePlugin extends Module
{
    public function handle(array $params): array
    {
        $aggs = $params[0] ?? [];
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
    protected function getBuckets($items, $agg, $append = []) : array
    {
        $val = $items['value'] ?? 0;
        if ($val)  {
            return [
                array_merge($append, [
                    'count' => $val,
                ]),
            ];
        }
        
        $res = [];
        $buckets = $items['buckets'] ?? [];
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
            
            if (!$itBucket) {
                $res[] = array_merge($append, [
                    $agg    => $item['key'],
                    'count' => $item['doc_count'],
                ]);
            }
        }

        return $res;
    }
}
