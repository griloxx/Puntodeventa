<?php

namespace App;

class Articulos extends ActiveRecord {

    protected static $tabla = 'articulos';
    protected static $columnasDB = ['id','codigo', 'nombre', 'base', 'iva', 'pvp', 'stock', 'categoriasId', 'precioCompra', 'baseCompra'];

    public $id;
    public $codigo;
    public $nombre;
    public $base;
    public $iva;
    public $pvp;
    public $stock;
    public $categoriasId;
    public $precioCompra;
    public $baseCompra;

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->codigo = $args['codigo'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->base = $args['base'] ?? '';
        $this->iva = $args['iva'] ?? '';
        $this->pvp = $args['pvp'] ?? '';
        $this->stock = $args['stock'] ?? '';
        $this->categoriasId = $args['categoriasId'] ?? '';
        $this->categoriasId = $args['precioCompra'] ?? '';
        $this->categoriasId = $args['baseCompra'] ?? '';
    }
    public function buscar($busqueda) {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE nombre like '%{$busqueda}%'";
        $query2 = "SELECT * FROM " . static::$tabla . " WHERE codigo = '{$busqueda}'";
        
        $resultado = self::consultarSQL($query);
        if(!$resultado) {
            $resultado = self::consultarSQL($query2);
        }
        return $resultado;
        
    }
    public function busqueda($busqueda) {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE nombre = '{$busqueda}'";
        $query2 = "SELECT * FROM " . static::$tabla . " WHERE codigo = '{$busqueda}'";
        
        $resultado = self::consultarSQL($query);
        if(!$resultado) {
            $resultado = self::consultarSQL($query2);
        }
        return array_shift($resultado);
    }
    public function codigo($codigo, $dir) {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE codigo = '{$codigo}'";
        
        $resultado = self::consultarSQL($query);
        $resultado = array_shift($resultado);
        
        if($resultado) {
            header('Location: ' . $dir . '?r=8');
            exit;
        } 
    }
    public function actualizarStock() {

        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }

        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' " . " LIMIT 1 ";// para db solo es una conexion en la clase padre se deja self
        
        self::$db->query($query);


    }
    public static function findNombre($nombre) {
        $query = " SELECT * FROM " . static::$tabla . " WHERE nombre = '{$nombre}'";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
        
    }
}