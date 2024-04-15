<?php
//isnew
//label()
//getbyid
//properties

abstract class DataBaseQueryModel{
    private $TABLE_NAME; //or const
    public $isNew;
    public $connection;

    public function __construct() {
        //get the coonection
    }

    public abstract function labels();
}
?>