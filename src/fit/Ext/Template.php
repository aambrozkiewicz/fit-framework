<?php

namespace fit\Ext;

use \fit\App;

class Template implements \fit\ExtInterface
{
	public function register(App $app, array $values = array())
	{
		$app['tpl'] = $app->share(function($filepath) use ($values) {
			return new \fit\Template($values['basepath'] . $filepath);
		});
	}
}
