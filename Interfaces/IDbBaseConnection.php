<?php
interface IDbBaseConnection{

    public function connect() : bool;

    public function getConnection() : bool;
}
?>