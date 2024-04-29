<?php 

require_once 'Config/config.php';

class DbConnection implements IDbBaseConnection{

	private $host;
    private $port;
	private $db;
	private $user;
	private $pass;
	public PDO $conection;

    public bool $IsAlive = false;

	public function __construct(Array $conectionConfig = null) {
        $ExistCustomConfig = $conectionConfig != null && !empty($conectionConfig);

        $this->host = ($ExistCustomConfig && isset($conectionConfig["DB_HOST"])) ? $conectionConfig["DB_HOST"] : constant('DB_HOST');
        $this->port = ($ExistCustomConfig && isset($conectionConfig["PORT"])) ? $conectionConfig["PORT"] : constant('PORT');
        $this->db = ($ExistCustomConfig && isset($conectionConfig["DB"])) ? $conectionConfig["DB"] : constant('DB');
        $this->user = ($ExistCustomConfig && isset($conectionConfig["DB_USER"])) ? $conectionConfig["DB_USER"] : constant('DB_USER');
        $this->pass = ($ExistCustomConfig && isset($conectionConfig["DB_PASS"])) ? $conectionConfig["DB_PASS"] : constant('DB_PASS');
	}

	public function open(){
		try {
            $this->conection = new PDO('mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->db, $this->user, $this->pass);
            $this->IsAlive = true;
        } catch (PDOException $e) {
            $this->IsAlive = false;
            throw new Exception($e->getMessage());
        }
	}

	public function close() {
		try {
            $this->conection = null;
            $this->IsAlive = false;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
	}

	public function execute(String $SqlQuery, array $BindValues = null, bool $IsNonQuery = false, bool $AllRecords = false) {
        try {
            $QueryExecute = $this->conection->prepare($SqlQuery);
            
            if ($BindValues != null) 
                $QueryExecute = $this->bindArrayValue($QueryExecute, $BindValues);

            if ($QueryExecute == null) throw new Exception("ERROR ::: THE STATEMENT CANNOT BE EXECUTED!!!");

            /* Colocar IF para validar si se ejcuto correctamente */
            if ($QueryExecute->execute()) {
                if(!$IsNonQuery)
                    return ($AllRecords) ? $QueryExecute->fetchAll() : $QueryExecute->fetch();
                else
                    return true;
                    //$QueryExecute->rowCount();
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