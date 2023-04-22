<?php

namespace App;


class Ticket extends ActiveRecord {

    protected static $tabla = 'ticket';
    protected static $columnasDB = ['id', 'nombre', 'precio', 'base', 'cantidad', 'total', 'ticketsId', 'codigo'];

    public $id;
    public $nombre;
    public $precio;
    public $base;
    public $cantidad;
    public $total;
    public $ticketsId;
    public $codigo;

    public function __construct($ars = [])
    {
        $this->id = $args['id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '0.00';
        $this->base = $args['base'] ?? '0.00';
        $this->cantidad = $args['cantidad'] ?? '1';
        $this->total = $args['total'] ?? '0.00';
        $this->ticketsId = $args['ticketsId'] ?? '';
        $this->codigo = $args['codigo'] ?? '0';
    }

     
    public function comprobar() {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY ticketsId DESC LIMIT 1";
        
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }
    public function comprobarId() {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT 1";
        
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }
    
    public function mostrarTicket($ticketsId) {
        // consultar en la base de datos
        $query = "SELECT * FROM " . static::$tabla . " WHERE ticketsId = {$ticketsId} ORDER BY ticketsId DESC";
        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    

    public function crear1() {

        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        $resultado = self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        
        if($resultado) {
            
           
             // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador
            header('Location: /venta.php');
            self::$db->close();
        }
    }
    public function crear2() {

        //sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        
        
        //INSERTAR EN LA BASE DE DATOS
        $query = " INSERT INTO " . static::$tabla . " ( " . join(', ', array_keys($atributos)) . " ) VALUES ('" . join("', '", array_values($atributos)) . "') ";
        $resultado = self::$db->query($query); // para db self porque las clases hijo utilizan la misma conexion
        
        
        if($resultado) {
            
           
             // asegurarse que se detiente la ejecucion del script y se envia la redireccion al navegador
            header('Location: /venta.php?r=7');
            self::$db->close();
        }
    }

    public function actualizar1($listado) {
        
        
        // actualizamos los atributos del objeto Ticket con los valores de $listado
        $this->id = $listado->id;
        $this->codigo = $listado->codigo;
        $this->nombre = $listado->nombre;
        $this->precio = $listado->precio;
        $this->base = $listado->base;
        $this->cantidad = $listado->cantidad;
        $this->total = $listado->total;
        $this->ticketsId = $listado->ticketsId;
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }
        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' " . " LIMIT 1 ";// para db solo es una conexion en la clase padre se deja self
        
        
        $resultado = self::$db->query($query);
        
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY ticketsId DESC LIMIT 1";
        $resultado = self::consultarSQL($query);
        $resultado = array_shift($resultado);
        $nuevoArticulo = new ticket;
        $nuevoArticulo->ticketsId = $resultado->ticketsId;
        $nuevoArticulo->crear1();

        if($resultado) {
            
            header('Location: /venta.php');
            self::$db->close();
            
        }

    }

    public function actualizar2($listado) {
        
        // actualizamos los atributos del objeto Ticket con los valores de $listado
        $this->id = $listado->id;
        $this->codigo = $listado->codigo;
        $this->nombre = $listado->nombre;
        $this->precio = $listado->precio;
        $this->base = $listado->base;
        $this->cantidad = $listado->cantidad;
        $this->total = $listado->total;
        $this->ticketsId = $listado->ticketsId;
        
        // sanitizar los datos
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores [] = "{$key}='{$value}'";
        }
        
        $query = " UPDATE " . static::$tabla . " SET " . join(', ', $valores) . " WHERE id = '" . self::$db->escape_string($this->id) . "' " . " LIMIT 1 ";// para db solo es una conexion en la clase padre se deja self
        $resultado = self::$db->query($query);
        

        if($resultado) {
            
            header('Location: /venta.php');
            self::$db->close();
            
        }

    }

    public function eliminar1() {
        // Eliminar registro

        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . static::$db->escape_string($this->id) . " LIMIT 1";
        
        $resultado = self::$db->query($query);// para db solo es una conexion en la clase padre se deja self
        
        if($resultado) {
            header("Location: /venta.php");
        }

    }
    public function eliminar2() {
        // Eliminar registro

        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . static::$db->escape_string($this->id) . " LIMIT 1";
        
        $resultado = self::$db->query($query);// para db solo es una conexion en la clase padre se deja self
        
        if($resultado) {
            return $resultado;
        }

    }
    
}
