<?php
estaAutenticado();
$imagenPerfil = $_SESSION['imagen'];
$user = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/app.css">
    <title>Punto de Venta</title>
</head>
<body class="body">
    <header class="contenedor contenido-header">
        <div class="logo">
            <img class="logo-menu" loading="lazy" src="/imagenes/logo.png" alt="Logo">            
        </div>
        <div class="usuario">
            <div>
                <a class="user" href=""><?php echo $user ?></a>
                <p class="fecha"><?php date_default_timezone_set('Europe/Madrid'); echo date("d-m-Y") ?></p>
            </div>                
                <img class="img-usuario" loading="lazy" src="/imagenes/<?php echo $imagenPerfil ?>" alt="Imagen Perfil">
        </div>
    </header>