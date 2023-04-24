<?php

function conectarDB() : mysqli {
<<<<<<< HEAD
    $db = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_BD']
    );
=======
    $db = new mysqli('', '', '', '');
>>>>>>> 72f2820cb08f4f9f4e4e0f39315c42d5f15c4510

    if (!$db) {
        echo 'Error en la conexion';
        exit;
    }
        return $db;
}
