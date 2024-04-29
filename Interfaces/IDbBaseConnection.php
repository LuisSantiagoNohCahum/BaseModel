<?php
interface IDbBaseConnection{

/*     public function connect() : bool;

    public function getConnection() : bool; */

    public function open();

    public function close();

    /* (String $SqlQuery, PDO $Connection, bool $AllRecords) */
    public function execute(String $SqlQuery, array $BindValues, bool $AllRecords);
}
?>