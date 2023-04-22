<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/includes/app.php';
require __DIR__ . '/vendor/mike42/escpos-php/autoload.php';

use App\Clientes;
use Mike42\Escpos\Printer;
// use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Empresa;
use App\Ticket;
use App\Totales;
$empresa = Empresa::all();
$empresa = $empresa[0];
$ticket = new ticket;
$ticket = $ticket->comprobar();
$ticketsId = $ticket->ticketsId - 1;
$ticket = $ticket->mostrarTicket($ticketsId);
$totales = new Totales;
$totales = $totales->comprobar();
$id = $totales->id - 1;
$totales = $totales->mostrarUltimo($id);
$total = $totales->total;
$subTotal = $totales->sub;
$iva = $subTotal * 0.21;
$iva = number_format($iva, 2);
$metodo = $totales->metodo;
$entregado = $totales->entregado;
$cambio = $totales->cambio;
$clienteId = $totales->cliente;
$cliente = new Clientes;
$cliente = $cliente->find($clienteId);
/**
 * Assuming your printer is available at LPT1,
 * simpy instantiate a WindowsPrintConnector to it.
 *
 * When troubleshooting, make sure you can send it
 * data from the command-line first:
 *  echo "Hello World" > LPT1
 */
try {
    $connector = new WindowsPrintConnector("$empresa->impresora");
    $printer = new Printer($connector);
    echo 1;
    // A FilePrintConnector will also work, but on non-Windows systems, writes
    // to an actual file called 'LPT1' rather than giving a useful error.
    // $connector = new FilePrintConnector("LPT1");

    /*
	Vamos a imprimir un logotipo
	opcional. Recuerda que esto
	no funcionará en todas las
	impresoras

	Pequeña nota: Es recomendable que la imagen no sea
	transparente (aunque sea png hay que quitar el canal alfa)
	y que tenga una resolución baja. En mi caso
	la imagen que uso es de 250 x 250
*/

// # Vamos a alinear al centro lo próximo que imprimamos
$printer->setJustification(Printer::JUSTIFY_CENTER);

/*
	Intentaremos cargar e imprimir
	el logo
*/
// try{
// 	$logo = EscposImage::load(__DIR__ . "/imagenes/geek.png", false);
//     $printer->bitImage($logo);
// }catch(Exception $e){/*No hacemos nada si hay error*/}

/*
	Ahora vamos a imprimir un encabezado
*/
# Vamos a alinear al centro lo próximo que imprimamos
// Alinear
$izquierda = $printer->setJustification(Printer::JUSTIFY_LEFT);
$derecha = $printer->setJustification(Printer::JUSTIFY_RIGHT);
$centro = $printer->setJustification(Printer::JUSTIFY_CENTER);

//impresion

$printer->setJustification(Printer::JUSTIFY_CENTER);

$printer->text("\n"."$empresa->nombre" . "\n");
$printer->text("\n"."$empresa->nombreComercial" . "\n");
$printer->text("$empresa->telefono" . "\n");
$printer->text("$empresa->email" . "\n");
$printer->text("$empresa->nif" . "\n");
$printer->text("$empresa->direccion" . "\n");
$printer->text("$empresa->cpPoblacion" . "\n");
$printer->text("-----------------------------" . "\n");

#La fecha también
date_default_timezone_set("Europe/Madrid");
$printer->text("Ticket: {$ticketsId} " . date("d-m-Y H:i") . "\n");
$printer->text("-----------------------------" . "\n");
// $printer->setJustification(Printer::JUSTIFY_LEFT);
/*
Ahora vamos a imprimir los
productos
*/
/*Alinear a la izquierda para la cantidad y el nombre*/
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("Cantidad Articulo Precio Total\n");
$printer->text("\n");
foreach($ticket as $articulo) {
	if($articulo->nombre !== "") {
		$printer->text("{$articulo->cantidad} {$articulo->nombre} {$articulo->precio}E {$articulo->total}E\n");
	}
}

/*
	Terminamos de imprimir
	los productos, ahora va el total
*/
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("-----------------------------"."\n");
$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("TOTAL {$total}E\n");
if ($metodo == "efectivo") {
	$printer->text("Entregado {$entregado}E\n");
	$printer->text("Cambio {$cambio}E\n");
}
$printer->text("Metodo de pago: {$metodo}\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("-----------------------------"."\n");

/*
	Podemos poner también un pie de página
*/

$printer->setJustification(Printer::JUSTIFY_RIGHT);
$printer->text("IVA 21% {$iva}E\n");
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("-----------------------------"."\n");
if($cliente->id !== '1') {
	$printer->text("Factura simplificada\n");
	$printer->text("\n$cliente->nombre\n");
	if($cliente->nombreEmpresa !== '') {
		$printer->text("$cliente->nombreEmpresa\n");
	}
	$printer->text("$cliente->apellidos\n");
	$printer->text("$cliente->nif\n");
	$printer->text("$cliente->direccion\n");
	$printer->text("$cliente->poblacionProvincia\n");
	$printer->text("$cliente->cp\n");
	$printer->text("$cliente->telefono\n");
	if($cliente->email !== '') {
		$printer->text("$cliente->email\n");
	}
	$printer->text("-----------------------------"."\n");
}
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text("$empresa->linea1\n");
$printer->text("$empresa->linea2\n");
$printer->text("$empresa->linea3\n");



/*Alimentamos el papel 3 veces*/
$printer->feed(5);

/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generará
	ningún error
*/
$printer->cut();

/*
	Por medio de la impresora mandamos un pulso.
	Esto es útil cuando la tenemos conectada
	por ejemplo a un cajón
*/
$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();

} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}