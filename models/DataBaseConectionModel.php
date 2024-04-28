<?php 

require_once 'Config/config.php';

class DbConnection implements IDbBaseConnection{

	private $host;
    private $port;
	private $db;
	private $user;
	private $pass;
	public PDO $conection;

	public function __construct() {		
		$this->host = constant('DB_HOST');
        $this->port = constant('PORT');
		$this->db = constant('DB');
		$this->user = constant('DB_USER');
		$this->pass = constant('DB_PASS');
	}

	public function open() : bool {
		try {
            $this->conection = new PDO('mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->db, $this->user, $this->pass);
            return true;
        } catch (PDOException $e) {
            return false;
        }
	}

	public function close() : bool {
		try {
            $this->conection = null;
            return true;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
	}

	public function execute(String $SqlQuery, array $BindValues = null, bool $NonQuery = false, bool $AllRecords = false) : mixed {
        try {
            $QueryExecute = $this->conection->prepare($SqlQuery);
            
            if ($BindValues != null) 
                $QueryExecute = $this->bindArrayValue($QueryExecute, $BindValues);

            if ($QueryExecute == null) throw new Exception("ERROR ::: THE STATEMENT CANNOT BE EXECUTED!!!");

            /* Colocar IF para validar si se ejcuto correctamente */
            if ($QueryExecute->execute()) {
                if(!$NonQuery)
                    return ($AllRecords) ? $QueryExecute->fetchAll() : $QueryExecute->fetch();
                else
                    return true;
            }else{
                return false;
            }

        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
	}

    private function bindArrayValue($Statement, $array, $typeArray = false)
    {
        try {
            if (is_object($Statement) && ($Statement instanceof PDOStatement)) {
                foreach ($array as $key => $value) {
                    if ($typeArray)
                        $Statement->bindValue(":$key", $value, $typeArray[$key]);
                    else {
                        if (is_int($value))
                            $param = PDO::PARAM_INT;
                        elseif (is_bool($value))
                            $param = PDO::PARAM_BOOL;
                        elseif (is_null($value))
                            $param = PDO::PARAM_NULL;
                        elseif (is_string($value))
                            $param = PDO::PARAM_STR;
                        else
                            $param = FALSE;
    
                        if ($param)
                        $Statement->bindValue(":$key", $value, $param);
                    }
                }

                return $Statement;
            }
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }


}

?>