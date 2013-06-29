<?php

namespace {
	function _gen_block_name($length = 5)
	{
		$alphabet = range('a', 'z') + range(1, 100);
		shuffle($alphabet);
		return join(array_slice($alphabet, 0, $length));
	}

	$GLOBALS['_blocks'] = [];
	$GLOBALS['_currentBlock'] = _gen_block_name();

	function _getBlocksContents()
	{
		global $_blocks;
		return join($_blocks);
	}

	function block($name)
	{
		global $_blocks, $_currentBlock;
		$_blocks[$_currentBlock] = ob_get_contents();
		ob_end_clean();
		ob_start();
		$_currentBlock = $name;
	}

	function endblock()
	{
		global $_blocks, $_currentBlock;
		$_blocks[$_currentBlock] = ob_get_contents();
		ob_end_clean();
		ob_start();
		$_currentBlock = _gen_block_name();
	}
} // global namespace

namespace fit {
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
			return $this;
		}
 
		public function render() 
		{
			extract($this->vars);
			ob_start();
			include($this->filepath);
			ob_end_clean();
			$contents = \_getBlocksContents();
			if (ob_get_length() > 0) {
				$contents .= ob_get_contents();
			}
			return $contents;
		}
	
		public function __toString()
		{
			return $this->render();
		}
	}
} // fit namespace
