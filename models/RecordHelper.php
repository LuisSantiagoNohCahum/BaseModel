<?php
//isnew
//label()
//getbyid
//properties

use function PHPSTORM_META\map;

abstract class RecordHelper implements IDbBaseModel{
    
    protected $table_name;
    public $isNew;
    public $isFillModel;
    public $connection;

    public function __construct() {
        
    }

    public function select(array $QueryConditions = null): mixed
    {
        try {
            
        } catch (\PDOException $th) {
            throw $th->getMessage();
        }
    }

    public function insert(): int
    {
        try {
            $insertValues = $this->getValues();

            $table_columns = array_keys($insertValues);
            $table_values = array_values($insertValues);

            if (!$this->isFillModel || empty($insertValues))
                throw new Exception("SimpleORMException ::: This model not load data to insert");

            if (empty($table_columns) or empty($table_values)) 
                throw new Exception("SimpleORMException ::: The array values to insert is empty");

            $SQL_QUERY_INSERT = "INSERT INTO %s (%s) VALUES (%s)";

            $ColumnsMapping = join(',', $table_columns);
            
            $BindingKeys = array_map(
                function ($item){
                    return sprintf(":%s", strval($item));
                }, $table_columns
            );

            $BindingKeysMapping = join(',', $BindingKeys);

            $SQL_QUERY_INSERT = sprintf($SQL_QUERY_INSERT, $this->table_name, $ColumnsMapping, $BindingKeysMapping);

            try {
                //return lastRecordInsert
            } catch (\PDOException $th) {
                throw $th->getMessage();
            }

            return 0;
        } catch (\Exception $th) {
            throw $th->getMessage();
        }
        
    }

    public function update(): int
    {
        try {
            $UpdateValues = $this->getValues();
            return 0;
        } catch (\PDOException $th) {
            throw $th->getMessage();
        }
    }

    public function delete(): bool
    {
        try {
            
            return true;
        } catch (\PDOException $th) {
            throw $th->getMessage();
        }
    }
    /**
    * Funcion para obtener el array con las claves y valores que se van a insertar en la base de datos.
    *
    * @return array
    * @throws Exception
    */

    function bindArrayValue($req, $array, $typeArray = false)
    {
        if (is_object($req) && ($req instanceof PDOStatement)) {
            foreach ($array as $key => $value) {
                if ($typeArray)
                    $req->bindValue(":$key", $value, $typeArray[$key]);
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
                        $req->bindValue(":$key", $value, $param);
                }
            }
        }
    }

    public abstract function getId() : array;

    public abstract function getValues() : array;

    public abstract function labels();

    public abstract function initData();
}
?>