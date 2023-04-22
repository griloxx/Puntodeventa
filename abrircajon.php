<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/includes/app.php';
require __DIR__ . '/vendor/mike42/escpos-php/autoload.php';
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
    echo 3;
    // A FilePrintConnector will also work, but on non-Windows systems, writes

	// Por medio de la impresora mandamos un pulso.
	// Esto es útil cuando la tenemos conectada
	// por ejemplo a un cajón

$printer->pulse();

/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();

} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}