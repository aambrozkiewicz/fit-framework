<?php

namespace fit\Ext;

use \fit\ExtInterface;
use \fit\App;

class DBExt implements ExtInterface
{
	public function register(App $app, array $values = array())
	{
		extract($values['db.params'], EXTR_PREFIX_ALL, 'param');
		
		$app['db'] = new \PDO($param_driver . ':host=' . $param_host . ';dbname=' . $param_dbname,
			$param_username,
			$param_passwd);
	}
}
