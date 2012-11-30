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
		if ($name == 'view_template_file') 
		{
			throw new Exception("Cannot bind variable named 'view_template_file'");
		}
	
		$this->vars[$name] = $value;
	}
 
	public function render() 
	{
		if (array_key_exists('view_template_file', $this->vars)) 
		{
			throw new Exception("Cannot bind variable called 'view_template_file'");
		}
		
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
