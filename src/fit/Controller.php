<?php

namespace fit;

class Controller
{
	use Observeable {
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
	
	public function output($args)
	{
		foreach (\array_intersect_key($this->converters, $args) 
			as $arg => $fn) {
				$args[$arg] = $fn($args[$arg]);
		}
		
		return call_user_func_array($this->callable, $args);
	}
	
	public function convert($arg, $fn)
	{
		$this->converters[$arg] = $fn;
		return $this;
	}
}
