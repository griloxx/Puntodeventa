<?php

namespace App;

class Usuarios extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email', 'password', 'usuario', 'nombre', 'apellidos', 'imagen'];

    public $id;
    public $email;
    public $password;
    public $usuario;
    public $nombre;
    public $apellidos;
    public $imagen;

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->usuario = $args['usuario'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
    }

    public function validar($ext) {

        if(!$this->usuario) {
            self::$errores[] = 'El usuario es obligatorio';
        }
        if(!$this->password) {
            self::$errores[] = 'El password es obligatorio';
        }

        return self::$errores;
    }
    public function validarContraseña() {
        if(strlen($this->password) > 15) {
            self::$errores[] = 'El password no puede contener mas de 15 carateres de largo';
        }
        return self::$errores;
    }
    public function validarExtendido($ext) {
        if(strlen($this->usuario) > 10) {
            self::$errores[] = 'El Usuario no puede contener mas de 10 carateres de largo';
        }
        
        if(strlen($this->password) < 8) {
            self::$errores[] = 'El password no puede contener menos de 8 carateres de largo';
        }
        if(!preg_match("#[0-9]#", $this->password)) {
            self::$errores[] = 'El password debe contener al menos un numero';
        }
        if(!preg_match("/[a-zA-Z]/", $this->password)) {
            self::$errores[] = 'El password debe contener al menos una letra';
        }
        if(strlen($this->email) > 60) {
            self::$errores[] = 'El Email no puede contener mas de 60 carateres de largo';
        }
        if(empty($this->email)) {
            self::$errores[] = 'El Email no puede estar vacio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = 'El Email no tiene un formato  válido';
        }
        if(strlen($this->nombre) > 30) {
            self::$errores[] = 'El Nombre no puede contener mas de 30 carateres de largo';
        }
        if(empty($this->nombre)) {
            self::$errores[] = 'El Nombre no puede estar vacio';
        }
        if(strlen($this->apellidos) > 60) {
            self::$errores[] = 'Los Apellidos no pueden contener mas de 60 carateres de largo';
        }
        if(empty($this->apellidos)) {
            self::$errores[] = 'El campo Apellidos no puede estar vacio';
        }
        if (!$this->imagen) {
            self::$errores[] = "La imagen es obligatoria";
        }
        if ($ext == '') {
            
        }else if ($ext !== 'jpg') {
            self::$errores[] = "La imagen tiene que ser .JPG";
        }
        $errores = $this->validar($ext);
        // Combinar los errores originales y nuevos
        $errores = array_merge($errores, self::$errores);
        
        return $errores;

    }
    public static function find($id) {
        $query = " SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        if(!$resultado) {
            $errores = self::$errores[] = "El usuario no existe";
            return $errores;
        }
        return array_shift($resultado);
        
    }
    

    public function comprobarPassword($resultado) {
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);

        if(!$autenticado) {

            self::$errores[] = 'El password es incorrecto';
            
        } else {

            self::iniciarSesion($usuario->imagen);
        }
        
    }
    public function iniciarSesion($imagen) {
         
        session_start();

        // LLenar el arreglo de session
        $_SESSION['usuario'] = $this->usuario;
        $_SESSION['imagen'] = $imagen;
        $_SESSION['login'] = true;

        header('Location: /menu.php');

    }
    public function eliminar($dir) {
        // Eliminar registro
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . static::$db->escape_string($this->id) . " LIMIT 1";
        
        $resultado = self::$db->query($query);// para db solo es una conexion en la clase padre se deja self
        
        if($_SESSION['usuario'] === $this->usuario) {
            $this->borrarImagen();
            header('Location: /cerrar-sesion.php');
        } else if ($resultado) {
            $this->borrarImagen();
            header("Location: " . $dir . "?r=3");
        }
        

    }

}