<?php
require './includes/app.php';
incluirTemplates('header');

use App\Totales;
$informes = new Totales;
$informes = $informes->caja();
date_default_timezone_set("Europe/Madrid");
$sumaEfectivo = 0.00;
$sumaTarjeta = 0.00;



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
    <h1 class="texto-centrado titulo">Informes</h1>
    <div class="separar-botones">
        <a href="/menu.php" class="boton-volver">Volver</a>
    </div>
    <div class="dividir-tablas">
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
            <h2 class="texto-centrado">Ticket</h2>
            <table class="usuarios">
                <thead>
                    <tr>
                        <th>Ticket Id</th>
                        <th>Metodo de pago</th>
                        <th>Subtotal</th>
                        <th>Total</th>
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
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div>
            <h2 class="texto-centrado">Total</h2>
            <table class="usuarios">
                <thead>
                    <tr>
                        <th>efectivo</th>
                        <th>tarjeta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach($informes as $informe) {
                            if($informe->metodo == 'efectivo') {
                                $sumaEfectivo += $informe->total;
                            }
                            if($informe->metodo == 'tarjeta') {
                                $sumaTarjeta += $informe->total;
                            }
                        } ?>
                        <td>Total Efectivo: <?php echo $sumaEfectivo ?> €</td>
                        <td>Total tarjeta: <?php echo $sumaTarjeta ?> €</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total: <?php  $caja = $sumaEfectivo + $sumaTarjeta; echo $caja ?> €</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
incluirTemplates('footer');
?>