<?php

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require 'funciones.php';
require 'config/database.php';

// conectarnos a la base de datos

$db = conectarDB();

use App\ActiveRecord;

ActiveRecord::setDB($db);