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

}