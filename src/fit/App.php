<?php

namespace fit;

class App extends \Pimple
{
	use Observeable;
	
	private $controllers = array();
	
	public function register(ExtInterface $ext, array $values = array())
	{
		$ext->register($this, $values);
		return $this;
	}

	public function get($regex, $callable, $name = null)
	{
		return $this->match('GET', $regex, $callable, $name);
	}
	
	public function post($regex, $callable, $name = null)
	{
		return $this->match('POST', $regex, $callable, $name);
	}
	
	protected function match($method, $regex, $callable, $name)
	{
		$regex = str_replace('/', '\/', $regex);
		$regex = '^' . $regex . '\/?$';
		return $this->controllers[$method][$regex] = new Controller($regex, $callable, $name);
	}
	
	public function abort($code, $msg = null)
	{
		throw new Exception($msg, $code);
	}
	
	public function redirect($path)
	{
		header('Location: ' . $path);
	}

	public function run()
	{
		$method = strtoupper($_SERVER['REQUEST_METHOD']);
		$path = strtok($_SERVER['REQUEST_URI'], '?');

		$methodControllers = isset($this->controllers[$method]) ? $this->controllers[$method] : array();
		
		try {
			foreach ($methodControllers as $ctrl) {
				if (($callable = $ctrl->match($path)) !== false) {
					echo $callable;
					return;
				}
			}

			$this->abort(404, 'Not found');
		} catch (Exception $e) {
			if (! $this->fire('error', $e)) {
				http_response_code($e->getCode());
				
			}
		}
	}
}
