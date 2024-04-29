<?php
require "interfaces/IDbBaseConnection.php";
require "models/DataBaseConectionModel.php";

require "interfaces/IDbBaseModel.php";
require "models/SimpleModel.php";
    /*
    * CREAR CONEXION CON EL ConectionModel
    */

    $con = new DbConnection();
    try {
        $con->open();
        if($con->IsAlive) echo "Conexion abierta";
        $res = $con->execute(
            "SELECT * FROM usuario where idusuario = :idusuario", 
            ["idusuario" => 5], 
            false, 
            true);

        echo var_dump($res);
    } catch (\Exception $ex) {
        echo $ex->getMessage();
    }

    $model = new SimpleModel();
    $model->nombre = "Santiago";
    $model->apellido = "Noh";
    //guardar o actualizar
    $model->save();

    //eliminar actual y resetear values - solo si id>0 o isnew false
    $model->delete();

    //buscar elemento y llenar el object con esos datos
    $model->getById();

    //devolver un array de objectos o array normal y asignar los valores a multiples objetos en for
    $model->getAll();

    //cargar datos para no asignar propiedades explicitamente - agregar HtmlSpecialChars ahi para evitar inyecion sql
    #$model->load();
    /*$model->load(
    *    ["data"=>"value"]
    * );
    * 
    */

    //add filterby para filtrar de acuerdo a ciertos criterios del mismo objeto

    //añadir funciones personalizadas ejecutando el execute y devolviendo un array

?>