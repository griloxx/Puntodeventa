<?php

function conectarDB() : mysqli {
    $db = new mysqli('', '', '', '');

    if (!$db) {
        echo 'Error en la conexion';
        exit;
    }
        return $db;
}
