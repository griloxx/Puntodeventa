<?php

namespace App;

class Empresa extends ActiveRecord {

    protected static $tabla = 'empresa';
    protected static $columnasDB = ['id', 'nif', 'nombreComercial', 'nombre', 'direccion', 'poblacion', 'cp', 'email', 'telefono', 'cpPoblacion', 'linea1', 'linea2', 'linea3', 'imagen', 'impresora'];

    public $id;
    public $nif;
    public $nombreComercial;
    public $nombre;
    public $direccion;
    public $poblacion;
    public $cp;
    public $email;
    public $telefono;
    public $cpPoblacion;
    public $linea1;
    public $linea2;
    public $linea3;
    public $imagen;
    public $impresora;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nif = $args['nif'] ?? '';
        $this->nombreComercial = $args['nombreComercial'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->poblacion = $args['poblacion'] ?? '';
        $this->cp = $args['cp'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->cpPoblacion = $args['cpPoblacion'] ?? '';
        $this->linea1 = $args['linea1'] ?? '';
        $this->linea2 = $args['linea2'] ?? '';
        $this->linea3 = $args['linea3'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->imagen = $args['impresora'] ?? '';
    }

}