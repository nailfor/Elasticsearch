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
    'aliases' => [
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
                'hosts'     => [env('ELASTICSEARCH_HOST', 'localhost:9200'),],
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

    //group "group" by 2 dates: before NOW-1Day and after. This is NOT filter, this is group by condition!
    //all groups with name "group" will be merged
    ->groupByRange(['group'=>'date.field'], ['ranges'=>['to' => 'now-1y/d', 'from' => 'now-1y/d']])

    //group "date.field" by interval "hour"
    ->groupByInterval(['graph'=>'date.field'], ['interval' => 'hour'])

    //group and calculate average value
    ->groupBy(['groupName' => 'field_for_group']) 
    ->groupByAverage('groupName', ['field' => 'field.name'])

    //group and calculate sum
    ->groupBy(['groupName' => 'field_for_group']) 
    ->groupBySum('groupName', ['field' => 'field.name'])

```


Credits
-------

- [nailfor](https://github.com/nailfor)

License
-------

The GNU License (GNU). Please see [License File](LICENSE.md) for more information.
