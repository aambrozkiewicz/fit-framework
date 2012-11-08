fit-framework
=============

Lean PHP >=5.3 framework, some of the features are taken from [Silex][1].

## Hello world
```php
require_once './autoload.php';

$app = new app;

$app->get('/([^/]+)/?', function($name) {
  return "Hello " . $name;
})->run();
```

[1]: http://silex.sensiolabs.org/

