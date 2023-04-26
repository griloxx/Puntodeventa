<?php

namespace App;

class Categorias extends ActiveRecord {

    protected static $tabla = 'categorias';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
    }

    public function validar($ext) {
        
        if(!$this->nombre) {
            self::$errores[] = 'El nombre es obligatorio';
        }
        if(strlen($this->nombre) > 20) {
            self::$errores[] = 'El nombre no puede contener mas de 20 carateres de largo';
        }
        return self::$errores;
    }
    public static function find($id) {
        $query = " SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        if(!$resultado) {
            header('Location: /admin/articulos/articulos.php?r=10');
        }else {
            return array_shift($resultado);
        }
    }
    public function validarEliminar() {
        if(empty($this->id)) {
            self::$errores[] = 'El id es obligatorio';
            return self::$errores;
        }
        $query = "SELECT * FROM articulos WHERE categoriasId = {$this->id}";
        $resultado = self::consultarSQL($query);
        
        if(!empty($resultado)) {
            header('Location: /admin/articulos/articulos.php?r=9');
        }
        
        
    }
}