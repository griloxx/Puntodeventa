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
    public function validar($ext) {
        
        if(!$this->nombre) {
            self::$errores[] = 'El nombre es obligatorio';
        }
        if(strlen($this->nombre) > 20) {
            self::$errores[] = 'El nombre no puede contener mas de 20 carateres de largo';
        }
        if(!$this->nif) {
            self::$errores[] = 'El NIF es obligatorio';
        }
        if(strlen($this->nif) > 12) {
            self::$errores[] = 'El NIF no puede contener mas de 12 carateres de largo';
        }
        if(!$this->nombreEmpresa) {
            self::$errores[] = 'El nombre de la empresa es obligatorio';
        }
        if(strlen($this->nombreEmpresa) > 50) {
            self::$errores[] = 'El nombre de la empresa no puede contener mas de 50 carateres de largo';
        }
        if(!$this->apellidos) {
            self::$errores[] = 'El campo apellidos es obligatorio';
        }
        if(strlen($this->apellidos) > 50) {
            self::$errores[] = 'El campo apellidos no puede contener mas de 50 carateres de largo';
        }
        if(!$this->direccion) {
            self::$errores[] = 'El campo direccion es obligatorio';
        }
        if(strlen($this->direccion) > 100) {
            self::$errores[] = 'El campo direccion no puede contener mas de 100 carateres de largo';
        }
        if(!$this->poblacionProvincia) {
            self::$errores[] = 'El campo poblacion provincia es obligatorio';
        }
        if(strlen($this->poblacionProvincia) > 70) {
            self::$errores[] = 'El campo poblacion provincia no puede contener mas de 70 carateres de largo';
        }
        if(!$this->cp) {
            self::$errores[] = 'El CP es obligatorio';
        }
        if(strlen($this->cp) > 10) {
            self::$errores[] = 'El CP no puede contener mas de 10 carateres de largo';
        }
        if(!$this->email) {
            self::$errores[] = 'El email es obligatorio';
        }
        if(strlen($this->email) > 70) {
            self::$errores[] = 'El email no puede contener mas de 70 carateres de largo';
        }
        if(!$this->telefono) {
            self::$errores[] = 'El telefono es obligatorio';
        }
        if(strlen($this->telefono) > 9) {
            self::$errores[] = 'El telefono no puede contener mas de 9 carateres de largo';
        }
        
        return self::$errores;
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