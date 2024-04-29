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
    public DbConnection $connection;

    public function __construct() {
        $connection = new DbConnection();
    }

    //empty array default to where
    public function select(array $QueryConditions = null): mixed
    {
        try {
            
        } catch (\PDOException $th) {
            throw $th->getMessage();
        }
    }

    public function where(){

    }
    
    public function insert(): int
    {
        try {

            $this->connection->open();

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
                $this->connection->execute($SQL_QUERY_INSERT, $insertValues, true);
                $insertId = $this->connection->conection->lastInsertId();

                //$this->getById($insertId);
                //or call setId($insertId);

                return $insertId;
            } catch (\PDOException $ex) {
                throw $ex;
            }

        } catch (\Exception $ex) {
            throw $ex;
        }finally{
            $this->connection->close();
        }
        
    }

    public function update(): int
    {
        try {
            $this->connection->open();

            $UpdateValues = $this->getValues();

            $table_columns = array_keys($UpdateValues);
            $table_values = array_values($UpdateValues);

            $_identityName = array_keys($this->getId())[0];
            $_identityValue = intval(array_values($this->getId())[0]);

            if (empty($UpdateValues))
                throw new Exception("SimpleORMException ::: This model not load data to insert");

            if (empty($table_columns) || empty($table_values)) 
                throw new Exception("SimpleORMException ::: The array values to insert is empty");

            if($_identityValue == 0) 
                throw new Exception("SimpleORMException ::: Cannot update this record because not exist in the database");
            
            $SQL_QUERY_INSERT = "UPDATE %s SET  %s WHERE %s = :id";
            
            $BindingKeys = array_map(
                function ($item){
                    return sprintf("%s = :%s", strval($item), strval($item));
                }, $table_columns
            );

            $BindingKeysMapping = join(',', $BindingKeys);

            $SQL_QUERY_INSERT = sprintf($SQL_QUERY_INSERT, $this->table_name, $BindingKeysMapping, $_identityName);

            $UpdateValues["id"] = $_identityValue;

            try {
                $res = $this->connection->execute($SQL_QUERY_INSERT, $UpdateValues, true);

                if($res) $this->getById($_identityValue);
                
                //return rowcount
                return $res;
            } catch (\PDOException $ex) {
                throw $ex;
            }

            return 0;
        } catch (\PDOException $ex) {
            throw $ex;
        }finally{
            $this->connection->close();
        }
    }

    public function delete(): bool
    {
        try {
            $_identityName = array_keys($this->getId())[0];
            $_identityValue = intval(array_values($this->getId())[0]);

            if (!$this->isNew && $_identityValue > 0) {
                $this->connection->open();

                $QueryBuilder = sprintf("DELETE FROM %s WHERE %s = :id", $this->table_name, $_identityName);
                $res = $this->connection->execute($QueryBuilder, ["id" => $_identityValue], true);

                if(!$res) throw new Exception("ERROR ::: Sucedio un error al intentar eliminar el registro actual");
                
                $this->isNew = true;
                $this->isFillModel = false;
            }else
                throw new Exception("ERROR ::: El registro actual no se puede eliminar por que no se encuentra en la base de datos");

            //agregar exception lineales para no anidar codigo
            return true;
        } catch (\PDOException $ex) {
            throw $ex;
        }finally{
            $this->connection->close();
        }
    }

    public function getById($IdentityValue) {
        try {
            $_identityName = array_keys($this->getId())[0];
            //$_identityValue = array_values($this->getId());

            //obtener con el id  con el param, si es con el id verificar que sea mayor a 0 si no mandar exception
            $this->connection->open();

            //id puede sustituirse con el nombre del id normal ":$_identityName"
            $QueryBuilder = sprintf("SELECT * FROM %s WHERE %s = :id", $this->table_name, $_identityName);

            $res = $this->connection->execute($QueryBuilder, ["id" => $IdentityValue]);

            if(!is_null($res) && !empty($res) ) $this->load($res);

            if($this->isFillModel) $this->isNew = false;

            $this->connection->close();
            //cambiar is new si load se cargo correctamente
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());

            //throw new Exception(msg)
            //throw $ex [ex is a exception object]
        }
    }

    public function getAll() : mixed {
        try {
            $this->connection->open();

            $QueryBuilder = sprintf("SELECT * FROM %s", $this->table_name);
            $res = $this->connection->execute($QueryBuilder, null, false, true);
            return $res;
            
        } catch (\Exception $ex) {
            throw new Exception($ex->getMessage());
        }finally{
            $this->connection->close();
        }
    }

    public abstract function getId() : array;

    public abstract function getValues() : array;

    public abstract function labels();

    public abstract function initData();

    public abstract function load(array $args);
}
?>