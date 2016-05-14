# Emissary
Allows you to easily use Laravel Service Providers with Slim 3's DI container, as well as the associated facades. Emissary is inspired by [itsgoingd/slim-services](https://github.com/itsgoingd/slim-services) and is compatible with Slim 3.

This is very much a work in progress so use at your own risk.

## Installation

Installation is via [Composer](https://getcomposer.org/):

```bash
$ composer require mikescott/emissary "1.*"
```

# A working example with Illuminate/Database
```php
<?php
require 'vendor/autoload.php';

use Slim\App;
use Slim\Container;

$config = [
    'settings' => [
        'database.fetch' => PDO::FETCH_CLASS,
        'database.default' => 'mysql',
        'database.connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => 'mysql',
                'port' => 3306,
                'database' => '',
                'username' => '',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ],
        ]
    ]
];

$providers = [
    'Illuminate\Database\DatabaseServiceProvider'
];

$aliases = [
    'DB' => 'Illuminate\Support\Facades\DB'
];

$app = new App(new Container($config));

$app->add(new \mikescott\Emissary\Middleware($providers, $aliases));

$app->get('/', function ($request, $response, $args) {
    # Illuminate/Database via facade
    $tables = DB::select('SHOW TABLES');
    var_dump($tables);

    # or via the container:
    $tables = $this->get('db')->select('SHOW TABLES');
    var_dump($tables);
});

$app->run();
```

## Custom Service Provider

### Example.php
```
<?php
namespace foo\Example;

class Example {
    public function hello()
    {
        return "Hello, world!";
    }
}
```

### ServiceProvider.php
See the [Laravel documentation](https://laravel.com/docs/5.1/providers#writing-service-providers) for full details about creating service providers.

```
<?php
namespace foo\Example;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
    public function register()
    {
        $this->app->singleton('example', function($app) {
           return new Example();
        });
    }
}
```

### Configuring Slim
```php
<?php
require 'vendor/autoload.php';

use Slim\App;
use Slim\Container;

$app = new App(new Container());

$app->add(new \mikescott\Emissary\Middleware([
    'foo\Example\ServiceProvider'
]));

$app->get('/', function ($request, $response, $args) {
    $response->write($this->get('example')->hello());
    return $response;
});

$app->run();
```

When you run the app, "Hello, world!" should be output from the Example service.

# License
The MIT License (MIT)

Copyright (c) 2016 Michael Scott (https://github.com/mikescott)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

