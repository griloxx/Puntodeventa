<?php

function conectarDB() : mysqli {
    $db = new mysqli('localhost', 'root', 'Gridur.91', 'puntoventa');

    if (!$db) {
        echo 'Error en la conexion';
        exit;
    }
        return $db;
}