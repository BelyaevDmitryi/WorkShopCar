<?php

class Loader
{
	public function loadClass($className) /*app\nameClass*/
	{
		$arr = explode('\\', $className);
		$prefix = array_shift($arr);

		if(count($arr)>1) {
			$prefixFile = '../app/';
		} elseif ($prefix == 'models') {
			$prefixFile = 'app/models/';
		} elseif ($prefix == 'controllers'){
			$prefixFile = 'app/controllers/';
		}

		$file = $prefixFile . implode('/', $arr) . '.php';

		if(is_file($file)){
			require_once $file;
		}
	}
}

?>