<?php
require "interfaces/IDbBaseConnection.php";
require "models/DataBaseConectionModel.php";

require "interfaces/IDbBaseModel.php";
require "models/RecordHelper.php";
require "models/SimpleModel.php";
    /*
    * CREAR CONEXION CON EL ConectionModel
    */

    $con = new DbConnection();
    try {
        $con->open();
        if($con->IsAlive) echo "Conexion abierta";
        $res = $con->execute(
            "SELECT * FROM personas where id = :idusuario", 
            ["idusuario" => 1], 
            false, 
            true);

        echo var_dump($res);
    } catch (\Exception $ex) {
        echo $ex->getMessage();
    }

    $model = new SimpleModel();
    $_POST = [
        "nombre" => "Jesus Israel",
        "apellido" => "Gamboa Garcia"
    ];

    //$model->load($_POST);
    //echo var_dump($model);
    
    //$model->nombre = "Santiago";
    //$model->apellido = "Noh";

    //guardar o actualizar
    //$model->save();
    //echo var_dump($model);

    //eliminar actual y resetear values - solo si id>0 o isnew false
    //$model->delete();

    //buscar elemento y llenar el object con esos datos
    //$model->getById(1);
    //echo var_dump($model);

    //update test
    //$model->load($_POST);
    //$model->save();
    //echo var_dump($model);

    //devolver un array de objectos o array normal y asignar los valores a multiples objetos en for
    $res = $model->getAll();
    foreach ($res as $obj) {
        $person = new SimpleModel();
        $person->load($obj);
        echo var_dump($person);
    }
    
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