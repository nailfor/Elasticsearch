# PHP Elasticsearch client for Laravel

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

# Example model
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

# Example select
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

# Example groups
```
esSearch::where('field.data', 'somedata')
    //group name "group" by field "data.field" without subgroups
    ->groupBy(['group'=>'data.field'])

    //group name "some_field" by field "some_field"
    ->groupBy('some_field')
    //subgroup name "another" by field "another" and subgroup name "diff" and field "diff.name"
    ->groupBy('some_field', ['another', 'diff'=>'field.diff'])

    //another style
    ->groupBy(['grp'=>'field'], ['subgrp'=>'sub.field'])

esSearch::where('field.data', 'somedata')
    //group name "group" by field "data.field" without subgroups with limit 10 items
    ->groupBy(['group' => 'data.field'])
    ->limit(10)

//supported closure
esSearch::groupBy(['aggregation_name' => 'field.data'], fn ($query) => $query
    ->groupBy('some_field', fn ($subQuery) => $subQuery
        ->groupBy(['agg3' => 'another.field'])
    )
)
```

# Range aggregations
```
    //group field "price" by 3 group: <1000, 1000-2000 and >2000
    ->groupByRange('price', ['ranges'=>[['to' => '1000'], ['from' => '1000', 'to' => 2000], ['from'=>2000]]])
```

# Date aggregations
```
    //group "group" by 2 dates: before NOW-1Day and after. This is NOT filter, this is group by condition!
    //all groups with name "group" will be merged
    ->groupByDateRange(['group'=>'date.field'], ['ranges'=>['to' => 'now-1y/d', 'from' => 'now-1y/d']])
```

# Interval aggregations
```
    //group "date.field" by interval "hour"
    ->groupByInterval(['graph'=>'date.field'], ['interval' => 'hour'])
```

# Average aggregations
```
    //group and calculate average value
    ->groupBy(['groupName' => 'field_for_group'])
    ->groupByAverage('groupName', ['field' => 'field.name'])
```

# Min/max aggregations
```
    ->groupByMin('groupName', ['field' => 'field.name'])
    ->groupByMax('groupName', ['field' => 'field.name'])
```


# Sum aggregations
```
    //group and calculate sum
    ->groupBy(['groupName' => 'field_for_group'])
    ->groupBySum('groupName', ['field' => 'field.name'])
```

# Nested aggregations
```
//simple group 'nested_field' same of 'nested_field'
esSearch::groupByNested('nested_field')

    //group name 'nested_group' by field 'nested.field'
    ->groupByNested(['nested_group' => 'nested.field']) 

    //By closures
    ->groupByNested(['nested_group' => 'nested.field'], fn ($query) => $query
        ->groupBy('some_field', fn ($subQuery) => $subQuery
            ->groupBy(['agg3' => 'another.field'])
        )
    )
```

# Stats aggregations
```
esSearch::groupByStats('some_field')
    // or
    ->groupByStats(['group_name' => 'some_field'])

//You can combine them together
esSearch::groupByNested('nested_field', fn($query) => 
    $query->groupBy(['group' => 'field.id'], fn($query) => $query
        ->groupByStats(['values' => 'properties.value'])
    )
)
```

# Example fuzziness
```
$query = esSearch::query($searchString, [
    'fuzziness' => 1,
]);
$collection = $query->get();
```

# Example scroll API
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

# Example suggest request
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

# Example nested request
```
$query = esSeartch::select([])
    ->where('category.id', 2)   //Attention!
    ->nested('category')        //Condition category.id = 2 will be removed from the main body
    ->where('price', 100)       //But this condition will be added to the main body

    //also u can combine simple and complicated nested requests
    ->nested('brand', fn ($subQuery) => $subQuery           //$subQuery doesn't consist any conditions from the body
        ->whereIn('brand.id', ['Nike', 'Sony', 'LG'])
        ->nested('color', fn($subSubQuery) => $subSubQuery  //Also $subSubQuery doesn't consist conditions
            ->whereIn('color.group', ['red', 'green'])      //Both of them doesn't affected the body condition 'price = 100'
        )
    )

```

# Example bulk insert
```
$records = [
    [
        '_id' => 1,  //This is field MUST be into recordset, otherwise will be set uniqid()
        'field1' => 'data1',
        'field2' => 'data2',
    ],
    [
        '_id' => 2,
        'field1' => 'some data1',
        'field2' => 'some data2',
    ],

];
esSeartch::insert($records);
```

# Example post_filter
```
$query = esSearch::groupBy(['group' => 'field.id'])
    ->postFilter(fn($query) => $query
        ->where('field', 'value')
        ->nested('category')
    )
```

# Debug
$query = esSearch::query()
    ->dd(true) //default false
;

Credits
-------

- [nailfor](https://github.com/nailfor)

License
-------

The GNU License (GNU). Please see [License File](LICENSE.md) for more information.
