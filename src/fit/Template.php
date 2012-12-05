<?php

namespace fit;

class Template {
	private $vars = array();
	private $filepath;
	
	public function __construct($filepath)
	{
		$this->filepath = $filepath . '.html.php';
	}
 
	public function __get($name) 
	{
		return isset($this->vars[$name]) ? $this->vars[$name] : null;
	}
 
	public function __set($name, $value) 
	{
		$this->vars[$name] = $value;
	}
 
	public function render() 
	{		
		extract($this->vars);
		ob_start();
		include($this->filepath);
		return ob_get_clean();
	}
	
	public function __toString()
	{
		return $this->render();
	}
}
