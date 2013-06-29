<?php

namespace fit\Ext;

use \fit\App;

class Database implements \fit\ExtInterface
{
	protected $db;
	
	public function register(App $app, array $values = array())
	{
		$this->db = new \PDO($values['connection']);
		
		$app['db'] = $this;
	}
	
	public function query($sql, array $bind = array())
	{
		$stmt = $this->db->prepare($sql);
		$stmt->execute($bind);
		$this->lastInsertId = (int)$this->db->lastInsertId();
		return $stmt;
	}
}
