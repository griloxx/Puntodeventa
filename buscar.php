<?php
require './includes/app.php';

use App\Articulos;

    $busqueda = $_POST['buscar'];
    $buscar = new Articulos;
    $resultados = $buscar->buscar($busqueda);
    echo json_encode($resultados);
