<?php
namespace models;
class Connection{
	#Класс _connection - для соединения с бд и выполнения запросов
	#_connect() --- соединяемся с БД
	#exec() --- выполняем запросы
    protected $_conn;

    private $_host;
    private $_user;
    private $_password;
    private $_options;

    function __construct(){
    	$this->_host = 'mysql:dbname=car_workshop;_host=127.0.0.1;charset=utf8';
    	$this->_user = 'root';
    	$this->_password = '0000';
        $this->_options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
    }

    public function connect(){
    	if(is_null($this->_conn))
    	{    		
    		try
			{				
		    	$this->_conn = new \PDO($this->_host, $this->_user, $this->_password, $this->_options);
		    	$this->_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->_conn->exec("set names utf8");
                $this->_conn->exec('SET CHARACTER SET utf8');
                $this->_conn->exec('SET COLLATION_CONNECTION="utf8_general_ci"');
			}
			catch (\PDOException $e)
			{
		    	die('Подключение не удалось: ' . $e->getMessage());
			}
		}
    }

    function exec($sql_query,$isFetchAll){
    	if(!is_null($this->_conn)){
            try{
                if($isFetchAll == true)
                    return $result = $this->_conn->query($sql_query)->fetchAll();
                else
                    return $result = $this->_conn->query($sql_query);
            } catch (\PDOException $e) {
               die('QueryRunning: ' . $e->getMessage());
            }
    		
    	}   	    	
    }

    function quote($string){
        if(!is_null($this->_conn)){
            try{
                return $result = $this->_conn->quote($string);
            } catch (\PDOException $e) {
                die('Quote: ' . $e->getMessage());
            }
        }    
    }

    function prepare($sql, $args){
        if(!is_null($this->_conn)){
            try{
                $statement = $this->_conn->prepare($sql);
                if(count($args)<1){
                    return $statement->execute();
                } else{
                    return $statement->execute($args);
                }
            } catch (\PDOException $e) {
                die('Prepare: ' . $e->getMessage());
            }
        }        
    }

	function __destruct(){
		$this->_conn=null;
	}
}
?>