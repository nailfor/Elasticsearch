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
           ->get();
```


Credits
-------

- [nailfor](https://github.com/nailfor)

License
-------

The GNU License (GNU). Please see [License File](LICENSE.md) for more information.
