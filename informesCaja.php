<?php
require './includes/app.php';
incluirTemplates('header');

use App\Totales;
$informes = new Totales;
$informes = $informes->caja();
$sumaTarjeta = 0.00;
$sumaEfectivo = 0.00;

?>
<main class="contenedor seccion sesion">
    <h1 class="texto-centrado titulo">Informes de Caja</h1>
    <div class="separar-botones">
        <a href="/menu.php" class="boton-volver">Volver</a>
    </div>
    <div class="dividir-tablas">
        <div>
            <h2 class="texto-centrado">Ticket Efectivo</h2>
            <table class="usuarios">
                <thead>
                    <tr>
                        <th>Ticket Id</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($informes as $informe) { 
                    if ($informe->metodo == 'efectivo') {    
                        
                        $sumaEfectivo += $informe->total;
                    ?>
                    <tr>
                        <td><?php echo $informe->id?></td>
                        <td><?php echo $informe->sub?> €</td>
                        <td><?php echo $informe->total?> €</td>
                    </tr>
                    <?php }} ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total: <?php echo $sumaEfectivo  ?> €</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <h2 class="texto-centrado">Ticket Tarjeta</h2>
            <table class="usuarios">
                <thead>
                    <tr>
                        <th>Ticket Id</th>
                        <th>Subtotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($informes as $informe) { 
                    if ($informe->metodo == 'tarjeta') { 
                        
                        $sumaTarjeta += $informe->total;   
                    ?>
                    <tr>
                        <td><?php echo $informe->id?></td>
                        <td><?php echo $informe->sub?> €</td>
                        <td><?php echo $informe->total?> €</td>
                    </tr>
                    <?php }} ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total: <?php echo $sumaTarjeta  ?> €</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <h2 class="texto-centrado">Total Caja Abierta</h2>
            <table class="usuarios">
                <thead>
                    <tr>
                        <th>Caja</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total: <?php  $caja = $sumaEfectivo + $sumaTarjeta; echo $caja ?> €</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php
incluirTemplates('footer');
?>