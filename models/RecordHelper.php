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
                $this->connection->close();
                return $insertId;
            } catch (\PDOException $ex) {
                throw $ex->getMessage();
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


    public abstract function getId() : array;

    public abstract function getValues() : array;

    public abstract function labels();

    public abstract function initData();
}
?>