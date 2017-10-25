<?php
if(isset($_SESSION['login'])){
	if($_SESSION['login'] == "admin"){

		$conn = new models\Connection();
		$conn->connect();

		$tableContract = new models\Query("contract");
		$tableUser = new models\Query("user");

		$sql_query = $tableContract->selectColumn("login");
		$userLogin = $conn->exec($sql_query, true);

		$userLogin = call_user_func_array('array_merge', $userLogin);
		array_shift($userLogin);

		//телефон при регистрации
		$userPhone = array();
		for($i = 0; $i < count($userLogin); $i++){
			$tmp = array();
			$tmp['login'] = $conn->quote($userLogin[$i]);
			$sql_query = $tableUser->selectColumnWithParametrs("phone",$tmp);
			$tmpPhone = $conn->exec($sql_query, true);

			$tmpPhone = call_user_func_array('array_merge', $tmpPhone);
			array_shift($tmpPhone);

			$userPhone[] = $tmpPhone[0];
		}
		unset($tmpPhone);
		unset($tmp);

		//телефон при подаче заявки
		$userPhoneZ = array();
		$sql_query = $tableContract->selectColumn("phone");
		$userPhoneZ = $conn->exec($sql_query, true);

		$userPhoneZ = call_user_func_array('array_merge', $userPhoneZ);
		array_shift($userPhoneZ);

		//название заявки
		$nameZ = array();
		$sql_query = $tableContract->selectColumn("name");
		$nameZ = $conn->exec($sql_query, true);

		$nameZ = call_user_func_array('array_merge', $nameZ);
		array_shift($nameZ);

		//описание заявки
		$descriptionZ = array();
		$sql_query = $tableContract->selectColumn("description");
		$descriptionZ = $conn->exec($sql_query, true);

		$descriptionZ = call_user_func_array('array_merge', $descriptionZ);
		array_shift($descriptionZ);

		//фотография проблемы
		$photoName = array();
		$sql_query = $tableContract->selectColumn("image_id");
		$photoName = $conn->exec($sql_query, true);

		$photoName = call_user_func_array('array_merge', $photoName);
		array_shift($photoName);

		$photoType = array();
		$sql_query = $tableContract->selectColumn("type");
		$photoType = $conn->exec($sql_query, true);

		$photoType = call_user_func_array('array_merge', $photoType);
		array_shift($photoType);

		$photoDir = array();
		$sql_query = $tableContract->selectColumn("path");
		$photoDir = $conn->exec($sql_query, true);

		$photoDir = call_user_func_array('array_merge', $photoDir);
		array_shift($photoDir);

		$photoPath = array();
		for($i = 0; $i < count($photoName); $i++){
			$tmp[] = $photoDir[$i];
			$tmp[] = $photoName[$i];
			$tmp[] = $photoType[$i];
		}

		$tmp1 = "";
		for($i = 0; $i < count($tmp); $i=$i+3){
			$j = $i+2;
			$k = $i+1;
			if(empty($tmp[$i])){
				$tmp1 = "Изображение отсутствует";
			} else{
				$str = "$tmp[$i]$tmp[$k].$tmp[$j], 1";
				$tmp1 = explode(",", $str);
				$tmp1 = array_shift($tmp1);
			}
			$photoPath[] = $tmp1;
		}
		unset($tmp1);
		unset($tmp);
		unset($str);

		//дата подачи заявки
		$dateZ = array();
		$sql_query = $tableContract->selectColumn("date_create");
		$dateZ = $conn->exec($sql_query, true);

		$dateZ = call_user_func_array('array_merge', $dateZ);
		array_shift($dateZ);

		$tmp = array();
		$tmp = implode("|", $dateZ);
		$tmp = explode("-", $tmp);
		$tmp = implode("|", $tmp);
		$tmp = explode("|", $tmp);

		unset($dateZ);
		$dateZ = array();
		$tmp1 = "";
		for($i = 0; $i < count($tmp); $i=$i+3){
			$j = $i + 2;
			$k = $i + 1;
			$str = "$tmp[$j].$tmp[$k].$tmp[$i], 1";
			$tmp1 = explode(",", $str);
			$tmp1 = array_shift($tmp1);
			$dateZ[] = $tmp1;
		}
		unset($tmp1);
		unset($str);
	}
}
?>

<?php
include "/app/views/pages/myXml.php";

$html = new SimpleXMLElement($xmlstr);

$thead = $html->div[0]->table[0]->addChild("thead");
$trh = $thead->addChild("tr");
$trh->addChild("td","Пользователь");
$trh->addChild("td","Телефон");
$trh->addChild("td","Название заявки");
$trh->addChild("td","Описание проблемы");
$trh->addChild("td","Фотография проблемы");
$trh->addChild("td","Дата подачи заявки");

$tbody = $html->div[0]->table[0]->addChild("tbody");
for($i = 0;$i < count($userLogin); $i++){
	$trb = $tbody->addChild("tr");
	$trb->addChild("td",$userLogin[$i]);
	$trb->addChild("td",$userPhoneZ[$i]);
	$trb->addChild("td",$nameZ[$i]);
	$trb->addChild("td",$descriptionZ[$i]);	
	if($photoPath[$i]=="Изображение отсутствует"){
		$td = $trb->addChild("td","Изображение отсутствует");
	} else{
		$td = $trb->addChild("td");
		$img = $td->addChild("img");
		$img->addAttribute("src",$photoPath[$i]);
		$img->addAttribute("width","300");
	}	
	$trb->addChild("td",$dateZ[$i]);
}

$html->asXML("app/views/xml/table.xml");
?>