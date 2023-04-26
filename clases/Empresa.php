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
        $this->impresora = $args['impresora'] ?? '';
    }

    public function validar($ext) {

        if(strlen($this->nif) > 11) {
            self::$errores[] = 'El NIF no puede contener mas de 11 carateres de largo';
        }
        if(empty($this->nif)) {
            self::$errores[] = 'El NIF no puede estar vacio';
        }
        if(strlen($this->nombreComercial) > 20) {
            self::$errores[] = 'El Nombre Comercial no puede contener mas de 20 carateres de largo';
        }
        if(empty($this->nombreComercial)) {
            self::$errores[] = 'El Nombre Comercial no puede estar vacio';
        }
        if(strlen($this->nombre) > 20) {
            self::$errores[] = 'El Nombre no puede contener mas de 20 carateres de largo';
        }
        if(empty($this->nombre)) {
            self::$errores[] = 'El Nombre no puede estar vacio';
        }
        if(strlen($this->direccion) > 100) {
            self::$errores[] = 'La Dirección no puede contener mas de 100 carateres de largo';
        }
        if(empty($this->direccion)) {
            self::$errores[] = 'El Dirección no puede estar vacio';
        }
        if(strlen($this->poblacion) > 20) {
            self::$errores[] = 'La población no puede contener mas de 20 carateres de largo';
        }if(empty($this->poblacion)) {
            self::$errores[] = 'La población no puede estar vacio';
        }
        if(strlen($this->cp) > 6) {
            self::$errores[] = 'El Código postal no puede contener mas de 6 carateres de largo';
        }
        if(empty($this->cp)) {
            self::$errores[] = 'El Código postal no puede estar vacio';
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
        if (!$this->imagen) {
            self::$errores[] = "La imagen es obligatoria";
        }
        if ($ext == '') {
            
        }else if ($ext !== 'png') {
            self::$errores[] = "La imagen tiene que ser .PNG";
        }
        if(strlen($this->telefono) > 20) {
            self::$errores[] = 'El Teléfono no puede contener mas de 20 carateres de largo';
        }
        if(empty($this->telefono)) {
            self::$errores[] = 'El Teléfono no puede estar vacio';
        }
        
        
        return self::$errores;
    }

    public function validarExtendido() {

        if(strlen($this->cpPoblacion) > 60) {
            self::$errores[] = 'El campo CP, Poblacion, Provincia no puede contener mas de 60 carateres de largo';
        }
        if(empty($this->cpPoblacion)) {
            self::$errores[] = 'El campo CP, Poblacion, Provincia no puede estar vacio';
        }
        if(strlen($this->linea1) > 40) {
            self::$errores[] = 'El campo linea1 no puede contener mas de 40 carateres de largo';
        }
        if(empty($this->linea1)) {
            self::$errores[] = 'El campo linea1 no puede estar vacio';
        }
        if(strlen($this->linea2) > 40) {
            self::$errores[] = 'El campo linea2 no puede contener mas de 40 carateres de largo';
        }
        if(empty($this->linea2)) {
            self::$errores[] = 'El campo linea2 no puede estar vacio';
        }
        if(strlen($this->linea3) > 40) {
            self::$errores[] = 'El campo linea3 no puede contener mas de 40 carateres de largo';
        }
        if(empty($this->linea3)) {
            self::$errores[] = 'El campo linea3 no puede estar vacio';
        }
        // Validaciones originales
        $ext = null;
        $errores = $this->validar($ext);
        // Combinar los errores originales y nuevos
        $errores = array_merge($errores, self::$errores);

        return $errores;

    }

}