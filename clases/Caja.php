<?php

namespace App;

class Caja extends ActiveRecord {

    protected static $tabla = 'caja';
    protected static $columnasDB = ['id', 'apertura', 'entradas', 'total', 'estado'];

    public $id;
    public $apertura;
    public $entradas;
    public $total;
    public $estado;

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->apertura = $args['apertura'] ?? '0';
        $this->entradas = $args['entradas'] ?? '0';
        $this->total = $args['total'] ?? '0';
        $this->estado = $args['estado'] ?? 'abierto';
    }

    public function crearCaja() {

        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        $resultado = self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        if($resultado) {
            
            header("Location: /menu.php");
            exit; // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador

        }
    }
    public function comprobar() {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT 1";
        
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }
    public function actualizar5() {
        
        
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }
        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE estado = 'abierto' ";
        $resultado = self::$db->query($query);
        


        
        if($resultado) {

            self::$db->close();
            header('Location: /menu.php');
            
        }

    }
}