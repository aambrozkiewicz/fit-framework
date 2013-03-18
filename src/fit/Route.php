<?php

namespace fit;

class Route
{
	private $pattern;
	private $regex;
	private $asserts = [];
	private $compiled = false;
	
	public function __construct($pattern)
	{
		$this->pattern = $pattern;
	}
	
	private function compile()
	{
		$this->regex = '@^' . preg_replace_callback('/:(\w+)/', function($matches) {
			if (isset($this->asserts[$matches[1]]))
				$pattern = $this->asserts[$matches[1]];
			else
				$pattern = "[^/]+";
			return "(?<$matches[1]>$pattern)";
		}, $this->pattern) . '$@';
	}
	
	public function match($path)
	{
		! $this->compiled && $this->compile();
		$this->compiled = true;
		
		$found = preg_match($this->regex, $path, $matches);
		if ($found) {
			foreach ($matches as $key => $value) {
				if (is_numeric($key)) {
					unset($matches[$key]);
				}
			} 
		}
		return $found ? $matches : null;
	}
	
	public function assert($key, $regex)
	{
		$this->asserts[$key] = $regex;
		return $this;
	}
}
