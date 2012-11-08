<?php

function class_loader($classname)
{
	$file = './' . $classname . '.php';
    if (file_exists($file) && is_readable($file) && !class_exists($classname, false)) {
        require_once($file);
    } else {
        throw new Exception('Class cannot be found ( ' . $classname . ' )');
    }
}

spl_autoload_register('class_loader');
