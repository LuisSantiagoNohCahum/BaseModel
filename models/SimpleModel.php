<?php
//isnew
//label()
//getbyid
//properties

class SimpleModel extends RecordHelper{

    //si todos tienen id pues basta con declararlo como un atributo en la clase padre
    public $id;
    public $nombre;
    public $apellido;

    public function __construct() {
        $this->isNew = true;
        $this->table_name = 'personas';
        $this->initData();
    }

    public function initData()
    {
        $this->id = 0;
        //inicializar propiedades con valores vacios

        //o crear metodo para verificar si las propiedades no estan vacias al llamar a save, un metodo loaddata que pase array de valores o uno que se llame validate para poner el fill en true y que valide evidentemente que los campos esten llenos
    
        //EN EL CONTROLADOR HAY QUE LIMPIAR LOS INPUTS CON EL HTMLSPECIALCHARACTERS

        //INTERNAMENTE AL HACER EL SAVE LLAMAR AL METODO VALIDATE PRIMERO PARA VER SI LOS DATOS ESTAN LLENOS Y CAMBIAR EL ESTADO DE LA BANDERA ISFILLRECORD
    }

    public function getValues() : array
    {
        return [
            'nombre'=>$this->nombre,
            'apellido'=>$this->apellido,
        ];
    }

    public function getId() : array
    {
        return [
            'id'=>$this->id
        ];
    }

    //abstarc method
    public function load(array $_args){
        try {
            //Todos los campos deben se llamados igual que en la bd tanto en los forms como en los modelos
            if (is_null($_args)) throw new Exception("Load ::: No se cargaron los valores del objeto correctamente");

            $IsValid = empty($conectionConfig);

            //checar id si se elimina o se queda
            $this->id = (!$IsValid && isset($args["id"])) ? htmlspecialchars($args["id"]) : 0;
            $this->nombre = (!$IsValid && isset($args["nombre"])) ? htmlspecialchars($args["nombre"]) : "";
            $this->apellido = (!$IsValid && isset($args["apellido"])) ? htmlspecialchars($args["apellido"]) : "";
            
            $this->isFillModel = true;
            //load default values here or in other method
        } catch (\Exception $ex) {
            $this->isFillModel = false;
            throw new Exception($ex->getMessage());
        }
    }
    public function labels()
    {
        return [
            'id'=>'ID',
            'nombre'=>'NOMBRE',
            'apellido'=>'APELLIDO',
        ];
    }

    public function save() : int {
        try {
            //call validate (return true or false, if is false return exception)
            if(!$this->isFillModel) throw new Exception("No se puede guardar el elemento debido a que el objeto esta vacio");

            if ($this->isNew) {
                return $this->insert();
                //insertar el nuevo id a la propiedad id y cambiar estado de isnew
            }else{
                return $this->update();
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    //implements method to return all data objects for the array getall
    public function validate() : bool
    {
        //verificar las propiedades por si se asignan por medio de propiedades y no por el load
        return $this->isFillModel;
    }
}
?>