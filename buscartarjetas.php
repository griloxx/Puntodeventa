<?php
require './includes/app.php';

use App\Tregalo;

    $cobrar = $_POST['cobrar'];
    $tRegalo = $cobrar['tRegalo'];
    $buscar = new Tregalo;
    $resultados = $buscar->buscar($tRegalo);
    echo json_encode($resultados);
