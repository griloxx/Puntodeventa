<?php

namespace App;

class ActiveRecord {
    
    // Base de datos
    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    // Errores
    protected static $errores = [];

    // Definir la conexion a la DB
    public static function setDB($database) {
        self::$db = $database; // self porque las clases hijas van a llamar a esta misma db
    }

    // crear o actualizar (articulos, usuarios , empresa...)
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
            header("Location: " . $dir . "?id=$this->id&r=2");
            
            
            exit; // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador

        }

    }
   
    public function autenticar() {

        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE usuario = '" . self::$db->escape_string($this->usuario) . "' LIMIT 1";
        
        $resultado = self::$db->query($query);
    
        if(!$resultado->num_rows) {
            self::$errores[] = 'El usuario no existe';
            return;
        }
        
        return $resultado;
    }

    public function eliminar($dir) {
        // Eliminar registro
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . static::$db->escape_string($this->id) . " LIMIT 1";
        
        $resultado = self::$db->query($query);// para db solo es una conexion en la clase padre se deja self
        
        if($resultado) {
            $this->borrarImagen();
            header("Location: " . $dir . "?r=3");
        } 

    }

    // identificar y unir los atributos de la DB
    public function atributos() { // Mapear los datos del $_POST que se reciben en el formulario y tenerlos en memoria para poder sanitizarlos
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
        
    }
    public function sanitizarAtributos() { // Sanitizar los datos, para ello tienen que estar en memoria con el metodo atributos()
        
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
    // Subida de archivos
    public function setImagen($imagen) {
        // Eliminar la imagen previa
        if(!is_null($this->id) && $imagen) {
            //comprobar si existe el archivo
            $this->borrarImagen();
        }
        // Asignar al atributo de imagen el nombre de la imagen
        if($imagen) {
            $this->imagen = $imagen;
        }
    }
    public function borrarImagen() {
        // Eliminar el archivo
        $existeArchivo = CARPETA_IMAGENES . $this->imagen;
        
        if(is_file($existeArchivo)){
            unlink($existeArchivo);
        }
    }

    // Validacion
    public static function getErrores() {

        return static::$errores;
    }

    public function validar ($ext) {

        static::$errores = [];
        return static::$errores;
    }

    public static function all() {
        $query = " SELECT * FROM " . static::$tabla; 
        
        $resultado = self::consultarSQL($query);

        return $resultado;

    }

    public static function where($catId) {

        $query = " SELECT * FROM " . static::$tabla . " WHERE categoriasId = {$catId}";
        $resultado = self::consultarSQL($query);
        return $resultado;

    }
    
    public static function find($id) {
        $query = " SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
        
    }

    public static function consultarSQL($query) {
        // consultar la base de datos
        
        $resultado = self::$db->query($query);
        // iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar memoria
        $resultado->free();
        
        //Retornar los resultados
        return $array;
    }

    public static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    public function sincronizar($args = []) {
        foreach($args as $key => $value) {
            if(property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

}