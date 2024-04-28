<?php
interface IDbBaseConnection{

/*     public function connect() : bool;

    public function getConnection() : bool; */

    public function open() : bool;

    public function close() : bool;

    /* (String $SqlQuery, PDO $Connection, bool $AllRecords) */
    public function execute(String $SqlQuery, array $BindValues, bool $AllRecords) : mixed;
}
?>