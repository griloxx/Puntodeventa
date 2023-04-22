<?php

namespace App;

class Tregalo extends ActiveRecord {

    protected static $tabla = 'tregalo';
    protected static $columnasDB = ['id', 'importe', 'estado'];

    public $id;
    public $importe;
    public $estado;


    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->importe = $args['importe'] ?? '0';
        $this->estado = $args['estado'] ?? 'abierto';

    }

    public function crearTarjeta() {

        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        
    }

    public function buscar($busqueda) {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE id LIKE '{$busqueda}%' AND estado = 'abierto' ";
        
        $resultado = self::consultarSQL($query);
        
        return $resultado;
        
    }
    public function cerrarAbrir() {
        
        
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }
        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '$this->id' ";
        self::$db->query($query);
        
    }
    public function comprobar() {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT 1";
        
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }
    public function mostrarUltimo($id) {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id} ";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

}