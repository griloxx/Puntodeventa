<?php
require './includes/app.php';
incluirTemplates('header');

use App\Totales;
use App\Ticket;
use App\Tregalo;

$informes = new Totales;
$informes = $informes->caja();
date_default_timezone_set("Europe/Madrid");


if(isset($_POST['fecha'])) {
    $inicio = $_POST['fecha']['inicio'];
    $fin = $_POST['fecha']['fin'];
    if($inicio == '') {
        $inicio = date("Y-m-d");
    }
    if($fin == '') {
        $fin = date("Y-m-d");
    }
    $informes = new Totales;
    $informes = $informes->filtrarFechas($inicio, $fin);
}




?>
<main class="contenedor seccion sesion">
    <div class="separar-botones">
        <a href="/menu.php" class="boton-volver">Volver</a>
    </div>
        <?php foreach($informes as $informe) { 
                if($informe->tRegalo !== 0) {
                    $tRegalo = $informe->tRegalo;
                }
                $id = $informe->id;
                $mostrarTicket = new Ticket;
                $mostrarTicket = $mostrarTicket->mostrarTicket($id);
                $mostrarTicket = $mostrarTicket;
                $tarjeta = new Tregalo;
                $tarjeta = $tarjeta->mostrarUltimo($tRegalo)
                ?>
            <!-- ventana modal -->
            <div class="editarCat<?php echo $informe->id?> ventana-modal">
                <div class="modal3">
                    <button id="<?php echo $informe->id ?>" class="boton-rojo cerrarEditarCat<?php echo $informe->id?>">Cerrar</button>
                    <div class="contenido-modal3">
                        <div class="div-articulos">
                            <table class="articulos">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Base</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0.0;
                                    $subtotal = 0.0;
                                    foreach($mostrarTicket as $mostrar) { 
                                        if($mostrar->nombre !== "") { 
                                            $total += $mostrar->total;
                                            $sub = $mostrar->base * $mostrar->cantidad;
                                            $subtotal += $sub;
                                            ?>
                                    <tr>
                                        <td><?php echo $mostrar->nombre ?></td>
                                        <td><?php echo $mostrar->base ?></td>
                                        <td><?php echo $mostrar->precio ?></td>
                                        <td><?php echo $mostrar->cantidad ?></td>
                                        <td><?php echo $mostrar->total ?>€</td>
                                    </tr>
                                    <?php }
                                }?>
                                <?php if($tRegalo) {
                                    $total += $tarjeta->importe;
                                ?>
                                    <tr>
                                        <td>T. Regalo: <?php echo $tRegalo ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $tarjeta->importe ?>€</td>
                                    </tr>
                                <?php } ?>

                                    <tr>
                                        <td></td>
                                        <td>Subtotal: <?php echo number_format($subtotal, 2) ?>€</td>
                                        <td></td>
                                        <td></td>
                                        <td>Total: <?php echo number_format($total, 2) ?>€</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!--fin ventana modal -->
        <?php } ?>
    <div class="dividir-tablas2">
        <div>
            <form method="post" class="fechas">
                <label>Fecha de inicio:</label>
                <input type="date" name="fecha[inicio]" value="<?php echo s($_POST['fecha']['inicio'] ?? ''); ?>">
                <label>Fecha de fin:</label>
                <input type="date" name="fecha[fin]" value="<?php echo s($_POST['fecha']['fin'] ?? ''); ?>">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div>
            <h1 class="texto-centrado">Tickets</h1>
            <table class="usuarios">
                <thead>
                    <tr>
                        <th>Ticket Id</th>
                        <th>Metodo de pago</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                        <th>cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($informes as $informe) {                   
                    ?>
                    <tr>
                        <td><?php echo $informe->id?></td>
                        <td><?php echo $informe->metodo?></td>
                        <td><?php echo $informe->sub?> €</td>
                        <td><?php echo $informe->total?> €</td>
                        <td><?php echo $informe->cliente?></td>
                        <td class="ultimo"><button id="<?php echo $informe->id ?>" class="boton-verde abrirEditarCat<?php echo $informe->id?>">Ver</button></td>
                    </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
incluirTemplates('footer');
?>