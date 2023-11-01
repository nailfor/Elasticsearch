# PHP Elasticsearch client for Laravel
==========================

Elasticsearch client for Eloquent ORM

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require nailfor/elasticsearch
```
or add

```json
"nailfor/elasticsearch" : "*"
```
to the require section of your application's `composer.json` file.

Usage
-----

Add config/app.php

```
    'providers' => [
        ...
        nailfor\Elasticsearch\ElasticsearchServiceProvider::class,

```
and config/database.php
```
    'connections' => [
        ...
        'elasticsearch' => [ //the name of connection in your models(default)
            'driver'    => 'elasticearch',
            'config'    => [
                'BasicAuthentication' => [
                    env('ELASTICSEARCH_LOGIN', ''),
                    env('ELASTICSEARCH_PASSWORD', ''),
                ],
                'hosts'     => [
                    env('ELASTICSEARCH_HOST', 'localhost:9200'),
                ],
                'retries'   => 1,
            ],
        ],

```

Example model
```
namespace App\Models\db\elastic;

use nailfor\Elasticsearch\Eloquent\Model;

class esSearch extends Model
{
    //protected $connection = 'elasticsearch'; //(default)

    //your index name
    protected $table='index';
}
```

Example select
```
esSearch::where('field', 'somedata')
    ->whereBetween('@timestamp', [$startDate, $endDate])
    ->whereIn('data.field', [1,3,4])
    ->whereFiledExist('some.field')
    ->whereFiledNotExist('another.field')
    ->get();
```

!!!ATTENTION!!!
After v0.17.0 groups returns the query result!

Example groups
```
esSearch::where('field.data', 'somedata')
    //group name "group" by field "data.field" without subgroups
    ->groupBy('group'=>'data.field') 

    //group name "some_field" by field "some_field"
    ->groupBy('some_field') 
    //subgroup name "another" by field "another" and subgroup name "diff" and field "diff.name"
    ->groupBy('some_field', ['another', 'diff'=>'field.diff']) 

    //another style
    ->groupBy(['grp'=>'field'], ['subgrp'=>'sub.field'])

    //group field "price" by 3 group: <1000, 1000-2000 and >2000
    ->groupByRange('price', ['ranges'=>[['to' => '1000'], ['from' => '1000', 'to' => 2000], ['from'=>2000]])

    //group "group" by 2 dates: before NOW-1Day and after. This is NOT filter, this is group by condition!
    //all groups with name "group" will be merged
    ->groupByDateRange(['group'=>'date.field'], ['ranges'=>['to' => 'now-1y/d', 'from' => 'now-1y/d']])

    //group "date.field" by interval "hour"
    ->groupByInterval(['graph'=>'date.field'], ['interval' => 'hour'])

    //group and calculate average value
    ->groupBy(['groupName' => 'field_for_group']) 
    ->groupByAverage('groupName', ['field' => 'field.name'])

    //group and calculate sum
    ->groupBy(['groupName' => 'field_for_group']) 
    ->groupBySum('groupName', ['field' => 'field.name'])

esSearch::where('field.data', 'somedata')
    //group name "group" by field "data.field" without subgroups with limit 10 items
    ->groupBy('group'=>'data.field')
    ->limit(10)

```


Example fuzziness
```
$query = esSearch::query($searchString, [
    'fuzziness' => 1,
]);
$collection = $query->get();
```

Example scroll API
```
$query = esSearch::scroll([
        'scroll' => '1m',
    ]);
$collection = $query->get(); //first 10k(max) records
$collection = $query->get(); //next 10k(max) records...
```
or
```
esSearch::scroll([
        'scroll' => '1m',
    ])
    ->chunk(1000, function ($collection) {
        ...
    });
```

Example suggest request
```
//clear
esSeartch::where('model', 'short')
    ->suggest('my-suggest')
    ->get();

//closure
esSeartch::suggest('my-clossure', fn ($query) => $query->where('color', 'black'))
    ->get();

//mix
$query = esSeartch::select([
        'brand',
        'name',
    ])
    ->query($searchString, [
        'minimum_should_match'=> '50%',
        'fuzziness' => 'auto',
    ])
    ->suggest('my-1', fn ($query) => $query->where('size', 'xxl'))
    ->suggest('my-2', fn ($query) => $query->where('color', 'black'))
    ->suggest('my-3') //do nothing because there no "where" section
    ;
$collection = $query->get();
```


Credits
-------

- [nailfor](https://github.com/nailfor)

License
-------

The GNU License (GNU). Please see [License File](LICENSE.md) for more information.
