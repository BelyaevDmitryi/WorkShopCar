<?php
namespace models;
class Query{
	#Класс Query --- отвечает за формирование запросов
	#На выходе должны получить строку запроса которую нужно выполнить
	#$tablename --- название таблицы с которой будем работать
	#getRecordById($id) --- получение строки запроса для необходимого id
	#getAllData() --- получение всех данных в таблице
	#getDataWithParametrs($params) --- получение данных по параметрам
	private $_tableName;
	function __construct($tableName){
		$this->_tableName = $tableName;
	}

	function selectRecordById($id){
		return $sql_query = "select * from $this->_tableName where id ='$id'";
	}

	function selectAllData(){
		
		return $sql_query = "select * from $this->_tableName";
	}

	function selectColumn($columnName){
		
		return $sql_query = "select $columnName from $this->_tableName";
	}

	function selectDataWithParametrs($params){
		$sql_query = "select * from $this->_tableName where ";
		foreach ($params as $key => $values) {
			$sql_query.= "$key = '$values' AND ";
		}
		return $sql_query = substr($sql_query,0,-4);
	}

	function selectColumnWithParametrs($columnName,$params){
		$sql_query = "select $columnName from $this->_tableName where ";
		if(count($params) == 0){
			$sql_query = substr($sql_query,0,-6);
		} else {
			foreach ($params as $key => $values) {
				$sql_query.= "$key = '$values' AND ";
			}
			$sql_query = substr($sql_query,0,-4);
		}
		return $sql_query;
	}

	function insertDataWithParametrs($fieldsName,$params){
		$sql_query ="";
		if(count($fieldsName) == count($params)){
			$sql_query = "insert into $this->_tableName (";
			foreach ($fieldsName as $key => $values) {
				$sql_query.="$values,";
			}
			$sql_query = substr($sql_query,0,-1);
			$sql_query = $sql_query .") values (";
			foreach ($params as $key => $values) {
				$sql_query.="'$values',";
			}
			$sql_query = substr($sql_query,0,-1);
			$sql_query = $sql_query .")";
		} elseif(count($fieldsName) > count($params)) {
			$sql_query = "Полей таблицы больше, чем параметров";
		} elseif(count($fieldsName) > count($params)) {
			$sql_query = "Параметров больше чем полей таблицы";
		}
		return $sql_query;
	}

	function updateDataWithParametrs($params){
		$sql_query = "update $this->_tableName set ";
		foreach ($params as $key => $values) {
			$sql_query.= "$key = '$values',";
		}
		$sql_query = substr($sql_query,0,-1);
		$sql_query = $sql_query." where ";

		$tmpParams = array();
		$tmpParams['login'] = $_SESSION['login'];
		$tmpParams['password'] = $_SESSION['password'];

		$conn = new app\Connection();
		$conn->connect();
		$tmp_query_sql = $this->selectDataWithParametrs($tmpParams);#получили запрос
		$result = $conn->exec($tmp_query_sql);#выполнили

		echo "<pre>";
		print_r($result);
		echo "</pre>";
	}
}
?>