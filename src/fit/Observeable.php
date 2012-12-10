<?php

namespace fit;

trait Observeable
{
	private $listeners = array();
	
	public function on($eventName, $callable)
	{
		$this->listeners[$eventName][] = $callable;
	}
	
	private function fire()
	{
		$args = func_get_args();
		$eventName = array_shift($args);
		$listeners = isset($this->listeners[$eventName]) ? $this->listeners[$eventName] : array();
		foreach ($listeners as $callable) {
			call_user_func_array($callable, $args);
		}
		
		return count($listeners);
	}
}
