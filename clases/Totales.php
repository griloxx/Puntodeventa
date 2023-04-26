<?php

namespace App;
date_default_timezone_set('Europe/Madrid');


class Totales extends ActiveRecord {

    protected static $tabla = 'totaltickets';
    protected static $columnasDB = ['id', 'sub', 'tRegalo', 'total', 'metodo', 'estado', 'entregado', 'cambio', 'fecha', 'cliente'];

    public $id;
    public $sub;
    public $tRegalo;
    public $total;
    public $metodo;
    public $estado;
    public $entregado;
    public $cambio;
    public $fecha;
    public $cliente;
    

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->sub = $args['sub'] ?? '0.00';
        $this->tRegalo = $args['tRegalo'] ?? '0';
        $this->total = $args['total'] ?? '0.00';
        $this->metodo = $args['metodo'] ?? '';
        $this->estado = $args['estado'] ?? 'abierto';
        $this->entregado = $args['entregado'] ?? '';
        $this->cambio = $args['cambio'] ?? '';
        $this->fecha = $args['fecha'] ?? date('Y-m-d');
        $this->cliente = $args['cliente'] ?? '1';
    }
    public function validar($ext) {
        if(empty($this->cliente)) {
            self::$errores[] = 'El id es obligatorio';    
        }
        if($this->cliente > 99999999) {
            self::$errores[] = 'El id es incorrecto';    
        }
        
        return self::$errores;
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
    public function consultarCierre() {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE estado = 'abierto' ";
        
        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }

    public function actualizar7() {

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
            header("Location: /venta.php");
            
            
            exit; // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador

        }

    }
    

    public function actualizar5() {
        
        
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }
        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' " . " LIMIT 1 ";// para db solo es una conexion en la clase padre se deja self
        
        $resultado = self::$db->query($query);
        


        
        if($resultado) {
            
            $nuevo = new Totales;
            
            $nuevo = $nuevo->crear5();

            self::$db->close();
            
        }

    }
    public function actualizar6() {
        
        
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }
        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' " . " LIMIT 1 ";// para db solo es una conexion en la clase padre se deja self
        $resultado = self::$db->query($query);
        


        
        if($resultado) {
            
            $nuevo = new Totales;
            
            $nuevo = $nuevo->crear6();

            self::$db->close();
            
        }

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

    

    public function crear5() {
        
        //sanitizar los datos
        
        $atributos = $this->sanitizarAtributos();
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        $resultado = self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        
        
        if($resultado) {
            
            $resultado = new Totales;
            $resultado = $resultado->comprobar();
            $nuevoTicket = new Ticket;
            $nuevoTicket = $nuevoTicket->comprobarId();
            $nuevoTicket = $nuevoTicket->eliminar2();
            $nuevoresultado = new Ticket;
            $nuevoresultado->ticketsId = $resultado->id;
            $nuevoresultado = $nuevoresultado->crear1();
        }
    }
    public function crear6() {
        
        //sanitizar los datos
        
        $atributos = $this->sanitizarAtributos();
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        $resultado = self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        
        
        if($resultado) {
            
            $resultado = new Totales;
            $resultado = $resultado->comprobar();
            $nuevoTicket = new Ticket;
            $nuevoTicket = $nuevoTicket->comprobarId();
            $nuevoTicket = $nuevoTicket->eliminar2();
            $nuevoresultado = new Ticket;
            $nuevoresultado->ticketsId = $resultado->id;
            $nuevoresultado = $nuevoresultado->crear2();
        }
    }
    public static function caja() {

        $query = " SELECT * FROM " . static::$tabla . " WHERE estado = 'abierto' AND total > 0 ";
        $resultado = self::consultarSQL($query);
        
        return $resultado;
    }
    public static function filtrarFechas($inicio, $fin) {

        $query = " SELECT * FROM " . static::$tabla . " WHERE fecha BETWEEN '{$inicio}' AND '{$fin}' AND total > 0 ";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
}