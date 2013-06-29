<?php

namespace fit;

class App extends \Pimple
{
	use Observeable;
	
	private $controllers = [];
	
	public function register(ExtInterface $ext, array $values = array())
	{
		$ext->register($this, $values);
		return $this;
	}

	public function get($pattern, $callable)
	{
		return $this->match('GET', $pattern, $callable);
	}
	
	public function post($pattern, $callable)
	{
		return $this->match('POST', $pattern, $callable);
	}
	
	protected function match($method, $pattern, $callable)
	{
		return $this->controllers[strtoupper($method)][] = new Controller($pattern, $callable);
	}
	
	public function abort($msg, $code = 0)
	{
		throw new Exception($code, $msg);
	}
	
	public function redirect($path)
	{
		header('Location: ' . $path);
	}
	
	public function request($key)
	{
		return isset($this->request[$key]) ? $this->request[$key] : null;
	}

	public function run()
	{
		$this->request = $_REQUEST;
		$method = strtoupper($_SERVER['REQUEST_METHOD']);
		$path = strtok($_SERVER['REQUEST_URI'], '?');

		$methodControllers = isset($this->controllers[$method]) ? $this->controllers[$method] : [];
		
		try {
			foreach ($methodControllers as $ctrl) {
				if (($args = $ctrl->match($path)) !== null) {
					$this->fire('before', $args);
					echo $ctrl->output($args);
					$this->fire('after', $args);
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
