fit-framework
=============

Micro framework. Glues route to a callable with some chain syntax sugar.
It is now capable of:
* Routing with named parameters
* Converting parameters to more complex objects before callable execution
* Extendable by extensions (register on App)
* Basic error handling
* App and each route are observeable, both emit `before` and `after`

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

$app->get('/:id', function($post) use ($app) {
	$tpl = $app['tpl']('posts');
	return $tpl->post = $post;
})->before(function() use($app) {
	if (true /* not logged in */) {
		$app->abort(401);
	}
})->assert('id', '\d+')->convert('id', function($id) use ($app) {
	return $app['db']->query('SELECT * FROM posts WHERE id=?', array($id))->fetchAll(PDO::FETCH_ASSOC);
});

$app->on('error', function($e) {
	echo 'Got error ' . $e->getCode();
});

$app->run();
```
