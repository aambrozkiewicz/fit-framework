fit-framework
=============

Very lean PHP >=5.4 framework.

## Example app
```php
require_once '../vendor/autoload.php';

$app = new fit\App;

$app['db'] = $app->share(function($c) {
	return new \PDO('sqlite:../db');
});
$app->register(new fit\Ext\Template, array(
	'basepath' => 'views/'
));

$app->get('/', function() use ($app) {
	$tpl = $app['tpl']('posts');
	$tpl->posts = $app['db']->query('SELECT * FROM entries')->fetchAll(PDO::FETCH_ASSOC);
	return $tpl;
})->before(function() use($app) {
	if (true /* not logged in */) {
		$app->abort(401);
	}
});

$app->on('error', function($e) {
	echo 'Got error ' . $e->getCode();
});

$app->run();
```

[1]: http://silex.sensiolabs.org/

