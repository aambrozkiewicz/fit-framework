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
		$this->fire('before', $this);
		if (preg_match("/{$this->regex}/i", $url, $matches)) {
			$content = call_user_func_array($this->callable, $matches);
			$this->fire('after', $this);
		}
		return empty($content) ? false : $content;
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
