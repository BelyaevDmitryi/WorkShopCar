<?php
namespace controllers;
use models\Query;
use models\Connection;

class MainController
{
	public function index()
	{
		header("Location: ");
	}

	public function login()
	{
		if(isset($_POST['submit'])){
			$params = array();
			if(empty($_POST['login'])){
				$_SESSION['error_login'] = "Введите Логин";
			} else{
				if(empty($_POST['password'])){
					$_SESSION['error_login'] = "Введите пароль";
				} else{
					if(is_string($_POST['login'])){
						if(is_string($_POST['password'])){
							$data = new Query("user");
							$conn = new Connection();
							$conn->connect();

							$params['login'] = $conn->quote($_POST['login']);
							$params['password'] = $conn->quote(hash('whirlpool',$_POST['password']));

							$query_sql = $data->selectDataWithParametrs($params);#запрос
							$result = $conn->exec($query_sql, true);#выполнили

							if(count($result)>0){
								$_SESSION['login'] = str_replace("'", "", $params['login']);
								header("Location:/");
							} else {
							//echo "Не правильный логин или пароль";
								$_SESSION['error_login'] = "Не правильный логин или пароль";
							}
						} else {
							$_SESSION['error_login'] = "Поле пароль должно быть строкой!";
						}
					} else {
						$_SESSION['error_login'] = "Поле логин должно быть строкой!";
					}
				}
			}		    
		} elseif(isset($_POST['reg'])){
			header("Location:/registration");
		}
	}

	public function reg()
	{
		if(isset($_POST['reg'])){
			$params = array();
			if(empty($_POST['login'])){
				header("Location:/registration");
				$_SESSION['error_reg'] = "Введите Логин";
			} else{
				if(empty($_POST['password'])){
					header("Location:/registration");
					$_SESSION['error_reg'] = "Введите пароль";
				} else{
					if(empty($_POST['phone'])){
						header("Location:/registration");
						$_SESSION['error_reg'] = "Введите телефон";
					} else {
						if(is_string($_POST['login'])){
							if(is_string($_POST['password'])){
								if(is_string($_POST['phone'])){
									$is_correct_phone = (bool)preg_match('/^(7){1}+(9){1}+\d{9}$/',$_POST['phone']);
									if($is_correct_phone == true){

										$conn = new Connection();
										$conn->connect();
										$tableUser = new Query("user");

										$params = array();
										$params['login'] = $conn->quote($_POST['login']);//экранируем и кладем в params
										$tmp_sql_query = $tableUser->selectDataWithParametrs($params);
										$tmp_result = $conn->exec($tmp_sql_query,true);
										if(count($tmp_result)<1){
											$params['password'] = $conn->quote(hash('whirlpool',$_POST['password']));
											$params['phone'] = $conn->quote($_POST['phone']);

											$fields = array();
											$fields['login'] = "login";
											$fields['password'] = "password";
											$fields['phone'] = "phone";

											$sql_query = $tableUser->insertDataWithParametrs($fields,$params);
											$result = $conn->exec($sql_query,false);

											if(count($result)>0){
												header("Location:/registration");
												$_SESSION['accept_reg'] = "Вы зарегестрировались! Перейдите по ссылке для <a href='/login'>авторизации</a>";
												exit();
											}
										} else {
											header("Location:/registration");
											$_SESSION['error_reg'] = "Такой логин уже существует! Придумайте другой логин. Если у вас уже есть логин перейдите по ссылке для <a href='/login'>авторизации</a>";
											exit();
										}
									} else {
										header("Location:/registration");
										$_SESSION['accept_reg'] = "Введите телефон в формате 79ххххххххх";
									}
								} else {
									$_SESSION['error_reg'] = "Поле телефон должно быть строкой!";
								}
							} else {
								$_SESSION['error_reg'] = "Поле пароль должно быть строкой!";
							}
						} else {
							$_SESSION['error_reg'] = "Поле логин должно быть строкой!";
						}
					}
				}
			}
		}
		if(isset($_POST['submit'])){
			header("Location:/login");
			exit();
		} else{
			exit();
		}
	}

	public function registration()
	{		
		include "/app/views/pages/registration.php";
		exit();
	}

	public function zayavka()
	{
		include "/app/views/pages/zayavka.php";
		exit();
	}

	public function logout()
	{
		session_unset();
		session_destroy();
		header("Location:/login");
	}

	public function allzayavki()
	{
		include "/app/views/pages/allzayavki.php";
		exit();
	}

	public function profile()
	{
		include "/app/views/pages/profile.php";
		exit();
	}

	public function download()
	{
		if(isset($_POST['download'])){
			$file = 'app/views/xml/table.xml';
			header("Content-Length: ".filesize($file));
			header("Content-Disposition: attachment; filename='table.xml'");
			readfile($file);
		}
	}

	public function addzayavka()
	{
		if(isset($_POST['send'])){
			if(!empty($_POST['name'])){
				if(is_string($_POST['name'])){
					if(is_string($_POST['phone'])){
						if(is_string($_POST['description']))
						{
							$conn = new Connection();
							$conn->connect();
							$params = array();
							$params['name'] = $conn->quote($_POST['name']);
							$params['phone'] = $conn->quote($_POST['phone']);
							$is_correct_phone = (bool)preg_match('/^(7){1}+(9){1}+\d{9}$/',$_POST['phone']);
							if($is_correct_phone == true){
								$params['description'] = $conn->quote($_POST['description']);
								if(strlen($_POST['description']) > 10) {

									$tableContract = new Query("contract");

									if(empty($_FILES['image']['name'])){
										$uploaddir = '';
										$loadfilename = $tableContract->selectAllData();//получаем все данные, смотрим последний id картинки
										$loadfilename = $conn->exec($loadfilename, true);//выполнили

										$tableUser = new Query("user");
										$tmp = array();
										$tmp['login'] = $conn->quote($_SESSION['login']);
										$sql_query = $tableUser->selectColumnWithParametrs("login",$tmp);
										$userLogin = $conn->exec($sql_query,true);

										echo "<pre>";
										print_r($userLogin);
										echo "</pre>";

										if(count($userLogin) > 0){
											unset($params);

											ini_set('date.timezone', 'Europe/Moscow');

											$params = array();
											$params['image_id'] = $conn->quote((count($loadfilename)+1));
											$params['login'] = $conn->quote($userLogin[0]['login']);
											$params['path'] = $conn->quote($uploaddir);
											$params['type'] = $conn->quote('');
											$params['phone'] = $conn->quote($_POST['phone']);
											$params['name'] = $conn->quote($_POST['name']);
											$params['description'] = $conn->quote($_POST['description']);
											$params['date_create'] = $conn->quote(date("y.m.d", time()));

											$fields = array();
											$fields['image_id'] = "image_id";
											$fields['login'] = "login";
											$fields['path'] = "path";
											$fields['type'] = "type";
											$fields['phone'] = "phone";
											$fields['name'] = "name";
											$fields['description'] = "description";
											$fields['date_create'] = "date_create";
											$sql_query = $tableContract->insertDataWithParametrs($fields,$params);
											$result = $conn->exec($sql_query,false);
											header("Location:/allzayavki");
											exit();
										} else {
											$_SESSION['error_zayavka'] = "Пользователь не найден";
											header("Location:/zayavka");
										}
									} else {
										$uploaddir = 'app/views/picture/';
										$loadfilename = $tableContract->selectAllData();//получаем все данные, смотрим последний id картинки
										$loadfilename = $conn->exec($loadfilename, true);//выполнили
										$type = explode(".",$_FILES['image']['name']);
										$uploadfile = $uploaddir . (count($loadfilename)+1) . "." . $type[1];
										if($_FILES['image']['size'] > 1024*6*1024) {
											$_SESSION['error_zayavka'] = "Размер файла превышает 6 мегабайт";
											exit();
										}
										if(is_uploaded_file($_FILES['image']['tmp_name'])){
											move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

											$tableUser = new Query("user");
											$tmp = array();
											$tmp['login'] = $conn->quote($_SESSION['login']);
											$sql_query = $tableUser->selectColumnWithParametrs("login",$tmp);
											$userLogin = $conn->exec($sql_query,true);

											if(count($userLogin) > 0){
												unset($params);

												ini_set('date.timezone', 'Europe/Moscow');

												$params = array();
												$params['image_id'] = $conn->quote((count($loadfilename)+1));
												$params['login'] = $conn->quote($userLogin[0]['login']);
												$params['path'] = $conn->quote($uploaddir);
												$params['type'] = $conn->quote($type[1]);
												$params['phone'] = $conn->quote($_POST['phone']);
												$params['name'] = $conn->quote($_POST['name']);
												//echo $params['name'];
												$params['description'] = $conn->quote($_POST['description']);
												$params['date_create'] = $conn->quote(date("y.m.d", time()));

												$fields = array();
												$fields['image_id'] = "image_id";
												$fields['login'] = "login";
												$fields['path'] = "path";
												$fields['type'] = "type";
												$fields['phone'] = "phone";
												$fields['name'] = "name";
												$fields['description'] = "description";
												$fields['date_create'] = "date_create";
												$sql_query = $tableContract->insertDataWithParametrs($fields,$params);
												$result = $conn->exec($sql_query,false);
												header("Location:/allzayavki");
												exit();
											} else {
												$_SESSION['error_zayavka'] = "Пользователь не найден";
												header("Location:/zayavka");
											}
										} else {
											$_SESSION['error_zayavka'] = "Ошибка загрузки файла";
											header("Location:/zayavka");
										}
									}
								} else {
									$_SESSION['error_zayavka'] = "Напишите более подробное описание проблемы!";
									header("Location:/zayavka");
								}
							} else {
								$_SESSION['error_zayavka'] = "Введите телефон в формате 79ххххххххх";
								header("Location:/zayavka");
							}
						} else{
							$_SESSION['error_zayavka'] = "Поле описание должно быть строкой!";
						}
					} else {
						$_SESSION['error_zayavka'] = "Поле телефон должно быть строкой!";
					}
				} else {
					$_SESSION['error_zayavka'] = "Поле название должно быть строкой!";
				}
			} else {
				$_SESSION['error_zayavka'] = "Отсутствует название";
				header("Location:/zayavka");
			}
		}
	}
}