<?php

namespace fit;

class App extends \Pimple
{
	private $controllers = array();
	private $error_handlers = array();
	
	public function register(ExtInterface $ext, array $values = array())
	{
		$ext->register($this, $values);
		return $this;
	}

	public function get($pattern, $callable)
	{
		$this->match('GET', $pattern, $callable);
		return $this;
	}
	
	public function post($pattern, $callable)
	{
		$this->match('POST', $pattern, $callable);
		return $this;
	}
	
	public function put($pattern, $callable)
	{
		$this->match('PUT', $pattern, $callable);
		return $this;
	}
	
	public function delete($pattern, $callable)
	{
		$this->match('DELETE', $pattern, $callable);
		return $this;
	}
	
	public function error($callable)
	{
		$this->error_handlers[] = $callable;
		return $this;
	}
	
	public function abort($msg, $code)
	{
		throw new Exception($msg, $code);
	}
	
	protected function match($method, $pattern, $callable)
	{
		$this->controllers[$method][$pattern] = $callable;
	}

	public function run()
	{
		$method = strtoupper($_SERVER['REQUEST_METHOD']);
		$path = $_SERVER['REQUEST_URI'];

		$method_controllers = array();
		if (isset($this->controllers[$method])) {
			$method_controllers = $this->controllers[$method];
		}
		
		try {
			foreach ($method_controllers as $regex => $callable) {
				$regex = str_replace('/', '\/', $regex);
				$regex = '^' . $regex . '\/?$';
				if (preg_match("/$regex/i", $path, $matches)) {
					$content = call_user_func_array($callable, array_slice($matches, 1)); 
					echo $content;
					return;
				}
			}

			throw new Exception('Not Found: ' . $path, 404);
		} catch (Exception $e) {
			$catched_once = false;
			foreach ($this->error_handlers as $err_handler) {
				$content = $err_handler($e);
				if ($content !== null) {
					echo $content;
					break;
				} else if ($content === false) { // break the chain
					break;
				}
				$catched_once |= true;
			}
			if (!$catched_once) {
				throw $e;
			}
		}
	}
}
