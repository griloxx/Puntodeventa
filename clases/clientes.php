<?php

namespace App;

class Clientes extends ActiveRecord {

    protected static $tabla = 'clientes';
    protected static $columnasDB = ['id', 'nombre', 'apellidos', 'nif', 'direccion', 'telefono', 'nombreEmpresa', 'email', 'poblacionProvincia', 'cp'];

    public $id;
    public $nombre;
    public $apellidos;
    public $nif;
    public $direccion;
    public $telefono;
    public $nombreEmpresa;
    public $email;
    public $poblacionProvincia;
    public $cp;

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->nif = $args['nif'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->nombreEmpresa = $args['nombreEmpresa'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->poblacionProvincia = $args['poblacionProvincia'] ?? '';
        $this->cp = $args['cp'] ?? '';
    }

    public function guardar($dir) {

        if(!empty($this->id)) {
            // actualizar
            $this->actualizar($dir);
        } else {
            // crear
            $this->crear($dir);
        }
    }
    public function crear($dir) {

        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        
        $resultado = self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        if($resultado) {
            
            // si el formulario se relleno correctamente
            // resetear post para que no se reenvie por acciones usuarios
            unset($_POST);
            // redireccionar al usuario mas mensaje get para utilizarlo en mensaje que muestre que se envió correctamente
            
            header("Location: " . $dir . "?r=1");
            exit; // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador

        }
    }

    public function actualizar($dir) {

        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }

        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' " . " LIMIT 1 ";// para db solo es una conexion en la clase padre se deja self
        
        $resultado = self::$db->query($query);
        
        if($resultado) {
            // si el formulario se relleno correctamente
            // resetear post para que no se reenvie por acciones usuarios
            unset($_POST);
            // redireccionar al usuario mas mensaje get para utilizarlo en mensaje que muestre que se envió correctamente
            header("Location: " . $dir . "?r=2");
            
            
            exit; // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador

        }

}
}