<?php

class tpl_ext implements extension
{
	public function register(app $app, array $values = array())
	{
		$app['tpl'] = function() {
			return new template;
		};
	}
}
