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
	
	protected function match($method, $pattern, $callable)
	{
		return $this->controllers[strtoupper($method)][] = new Controller($pattern, $callable);
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

		$methodControllers = isset($this->controllers[$method]) ? $this->controllers[$method] : [];
		
		try {
			foreach ($methodControllers as $ctrl) {
				if (($out = $ctrl->match($path)) !== false) {
					echo $out;
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
