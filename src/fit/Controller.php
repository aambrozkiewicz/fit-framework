<?php

namespace fit;

class Controller
{
	use Observeable {
		on as privte;
	}
	
	private $route;
	
	function __construct($regex, $callable, $name = null)
	{
		$this->regex = $regex;
		$this->callable = $callable;
		$this->name = $name ? $name : md5(date('U'));
	}
	
	public function match($url)
	{
		if (preg_match("/{$this->regex}/i", $url, $matches)) {
			$this->fire('before', $this);
			$content = call_user_func_array($this->callable, array_slice($matches, 1));
			$this->fire('after', $this);
			$found = true;
		}
		return empty($found) ? false : $content;
	}
	
	public function name($value)
	{
		$this->name = $value;
		return $this;
	}
	
	public function before($callable)
	{
		$this->on('before', $callable);
		return $this;
	}
	
	public function after($callable)
	{
		$this->on('after', $callable);
		return $this;
	}
}
