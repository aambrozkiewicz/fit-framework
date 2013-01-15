<?php

namespace fit;

class Controller
{
	use Observeable {
		on as privte;
	}
	
	private $route;
	private $converters = [];
	
	function __construct($pattern, $callable)
	{
		$this->route = new Route($pattern);
		$this->callable = $callable;
	}
	
	public function __call($mthd, $arguments)
	{
		if (method_exists($this->route, $mthd)) {
			return call_user_func_array(array($this->route, $mthd), $arguments);
		}
	}
	
	public function match($path)
	{
		if (($args = $this->route->match($path)) !== null) {
			$this->fire('before', $this);
			foreach (\array_intersect_key($this->converters, $args) 
				as $arg => $fn) {
					$args[$arg] = $fn($args[$arg]);
			}
			$content = call_user_func_array($this->callable, $args);
			$this->fire('after', $this);
			$found = true;
		}
		return empty($found) ? false : $content;
	}
	
	public function convert($arg, $fn)
	{
		$this->converters[$arg] = $fn;
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
