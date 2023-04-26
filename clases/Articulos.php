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
        $this->precioCompra = $args['precioCompra'] ?? '';
        $this->baseCompra = $args['baseCompra'] ?? '';
    }

    public function validar($ext) {
        
        if(!$this->nombre) {
            self::$errores[] = 'El nombre es obligatorio';
        }
        if(strlen($this->nombre) > 20) {
            self::$errores[] = 'El nombre no puede contener mas de 20 carateres de largo';
        }
        if(!$this->codigo) {
            self::$errores[] = 'El codigo es obligatorio';
        }
        if(strlen($this->codigo) > 9) {
            self::$errores[] = 'El codigo no puede contener mas de 9 carateres de largo';
        }
        if(!$this->base) {
            self::$errores[] = 'La base es obligatorio';
        }
        if(strlen($this->base) > 8) {
            self::$errores[] = 'La base no puede contener mas de 8 carateres de largo';
        }
        if(!$this->iva) {
            self::$errores[] = 'El iva es obligatorio';
        }
        if(strlen($this->iva) > 8) {
            self::$errores[] = 'El iva no puede contener mas de 8 carateres de largo';
        }
        if(!$this->pvp) {
            self::$errores[] = 'El pvp es obligatorio';
        }
        if(strlen($this->pvp) > 8) {
            self::$errores[] = 'El pvp no puede contener mas de 8 carateres de largo';
        }
        if(!$this->stock) {
            self::$errores[] = 'El stock es obligatorio';
        }
        if(strlen($this->stock) > 8) {
            self::$errores[] = 'El stock no puede contener mas de 8 carateres de largo';
        }
        if(!$this->precioCompra) {
            self::$errores[] = 'El precio de compra es obligatorio';
        }
        if(strlen($this->precioCompra) > 8) {
            self::$errores[] = 'El precio de compra no puede contener mas de 8 carateres de largo';
        }
        if(!$this->baseCompra) {
            self::$errores[] = 'La base de compra es obligatorio';
        }
        if(strlen($this->baseCompra) > 8) {
            self::$errores[] = 'La base de compra no puede contener mas de 8 carateres de largo';
        }
        
        return self::$errores;
    }
    public function validarActualizar($id) {
        $this->id = $id;
        if(!$this->id) {
            self::$errores[] = 'El Id es obligatorio';
        }
        if(strlen($this->id) > 9) {
            self::$errores[] = 'El Id no puede contener mas de 9 carateres de largo';
        }
        $ext = null;
        $errores = $this->validar($ext);
        // Combinar los errores originales y nuevos
        $errores = array_merge($errores, self::$errores);
        
        return $errores;
    }
    public function validarEliminar() {
        if(empty($this->id)) {
            self::$errores[] = 'El id es obligatorio';    
        }
        
        return self::$errores;
    }
    public function validarEliminarArt() {
        if(empty($this->id)) {
            self::$errores[] = 'El id es obligatorio';    
        }
        
        return self::$errores;
    }
    public static function find($id) {
        $query = " SELECT * FROM " . static::$tabla . " WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        if(!$resultado) {
            header('Location: /admin/articulos/articulos.php?r=11');
        }else {
            return array_shift($resultado);
        }
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