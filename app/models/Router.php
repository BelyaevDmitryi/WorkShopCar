<?php
namespace models;
class Router
{
	public function start()
	{
		$route = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

		$routing = [
			"/" => ['controller' => 'Main', 'action' => 'index'],
			"/login" => ['controller' => 'Main', 'action' => 'login'],
			"/registration" => ['controller' => 'Main', 'action' => 'registration'],
			"/pages" => ['controller' => 'Main', 'action' => 'pages'],
			"/logout" => ['controller' => 'Main', 'action' => 'logout'],
			"/zayavka" => ['controller' => 'Main', 'action' => 'zayavka'],
			"/allzayavki" => ['controller' => 'Main', 'action' => 'allzayavki'],
			"/profile" => ['controller' => 'Main', 'action' => 'profile'],
			"/download" => ['controller' => 'Main', 'action' => 'download'],
			"/addzayavka" => ['controller' => 'Main', 'action' => 'addzayavka'],
		];

		if(isset($routing[$route])){
			$controller = 'controllers\\' . $routing[$route]['controller'] . 'Controller';
			if(class_exists($controller)){
				$controller_obj = new $controller();
				if(method_exists($controller_obj, $routing[$route]['action'])){
					$controller_obj->$routing[$route]['action']();
				} else {
					$_SESSION['error_router'] = 'No method' . $routing[$route]['action'];
				}
			} else {
				$_SESSION['error_router'] = 'No classes ' . $controller;
			}
		} else {
			$_SESSION['error_router'] = 'No ways';
		}
	}
}
?>