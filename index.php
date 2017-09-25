<?php
use models\Connection;
use models\Router;

global $err;

session_start();

require '/app/models/Loader.php';
$loader = new Loader();
spl_autoload_register([$loader, 'loadClass']);

$conn = new Connection();
$conn->connect();

$router = new Router();
$router->start();

//echo $_SERVER['DOCUMENT_ROOT'];



if(isset($_SESSION['login']) and (empty($_SESSION['error_router'])) and (empty($_SESSION['error_login']))){
	include "/app/views/pages/main.php";
} else {
	include "/app/views/pages/login.php";
}
