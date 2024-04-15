<?php
interface IDbBaseModel{
    //GetById

    /*
    * [[where],[like],...]
    */
    public function select(array $QueryConditions) : mixed;

    //use table name in select method
    //public function select($WhereParams) : bool;

    public function insert() : int;

    public function update() : int;

    public function delete() : bool;

    //implements more database operatiosn with select : where, groupby, etc (These methods must return a string) and must concatenate with a select query for execute, 

    //llamar a los metodos de la clase abstracta de otra manera como insertInternal para que en el modelo base se llame simplemente insert, que llame al metodo de la clae padre y pasar las props
}
?>