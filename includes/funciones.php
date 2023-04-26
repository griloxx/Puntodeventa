<?php

define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones,php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');

use App\Articulos;
use App\Ticket;

function incluirTemplates(string $nombre) {
    include TEMPLATES_URL . "/{$nombre}.php";
}

function debugear($variable) {
    echo '<pre>';
    echo var_dump($variable);
    echo '</pre>';
}

function estaAutenticado() {
    session_start();
    if (!$_SESSION['login']) {
        return header('Location: /');
    }
}

// escapar / sanitizar el html

function s($html) :string {
    $s = htmlspecialchars($html);
    return $s;
}

function mostrarNotificaciones($codigo) {
    $mensaje = '';
    switch ($codigo) {
        case 1:
            $mensaje = 'Creado/a correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado/a correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado/a correctamente';
            break;
        case 4:
            $mensaje = 'La caja ya está abierta';
            break;
        case 5:
            $mensaje = 'La caja ya está cerrada';
            break;
        case 6:
            $mensaje = 'La caja está cerrada, debes abrirla primero';
            break;
        case 7:
            $mensaje = 'Tarjeta regalo creada Nº';
            break;
        case 8:
            $mensaje = 'El código utilizado ya existe';
            break;
        case 9:
            $mensaje = 'Esta categoria tiene articulos asociados, eliminelos primero o cambielos de categoria';
            break;
        case 10:
            $mensaje = 'Esta categoria no existe';
            break;
        case 11:
            $mensaje = 'Este articulo no existe';
            break;
        case 12:
            $mensaje = 'Error en los datos';
            break;
        
        default:
            $mensaje = false;
            break;
    }
    return $mensaje;
}
// function nuevoArticulos() {
//     $ticket = new Ticket;
//     $ticket = $ticket->comprobar();
//     if(empty($ticket->nombre)) {
//         $nuevosTicket = new Ticket;
//         $nuevosTicket->ticketsId = $ticket->ticketsId;
//     } else {
//         $nuevosTicket = new Ticket;
//         $nuevosTicket->ticketsId = $ticket->ticketsId + 1;
//         $nuevosTicket->crear1();
//     }
// }
function nuevoArticulo() {
    $ticket = new Ticket;
    $ticket = $ticket->comprobar();
    $nuevosTicket = new Ticket;
    $nuevosTicket->ticketsId = $ticket->ticketsId + 1;
    $nuevosTicket = $nuevosTicket->crear1();
}
