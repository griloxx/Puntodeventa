<?php
require './includes/app.php';
incluirTemplates('header');

use App\Empresa;
use App\Caja;
use App\Totales;


$r = $_GET['r'] ?? null;
$r = filter_var($r, FILTER_VALIDATE_INT);
$errores = [];
    
$empresa = Empresa::all();
$caja = new Caja;
$cierreCaja = new Caja;
$cierreCaja = $cierreCaja->comprobar();
$totales = new Totales;
$totales = $totales->caja();
$importeTotal = 0.00;
foreach($totales as $total) {
    if($total->metodo == 'efectivo') {
        $importeTotal += $total->total;
    }
}

if(isset($_POST['cierre'])) {
    $comprobarCierre = new Caja;
    $comprobarCierre = $comprobarCierre->comprobar();
    if($comprobarCierre->estado == 'cerrado') {
        header('Location: /menu.php?r=5');
    } else {
        $cierre = $_POST['cierre'];
        $cerrarCaja = new Caja;
        $cerrarCaja->apertura = $cierre['apertura'];
        $cerrarCaja->entradas = $cierre['entradas'];
        $cerrarCaja->total = $cierre['total'];
        $ext = null;
        $errores = $cerrarCaja->validar($ext);
        if(!$errores) {
            $cerrarTotales = new Totales;
            $cerrarTotales = $cerrarTotales->consultarCierre();
            foreach($cerrarTotales as $cerrarTotal) {
                $cerrarTotal->estado = 'cerrado';
                $cerrarTotal = $cerrarTotal->cerrarAbrir();
            }
            $cerrarCaja->estado = 'cerrado';
            $cerrarCaja = $cerrarCaja->actualizar5();
        } else {
            $errores = Caja::getErrores();
        }
    }
    
    

}
if (isset($_POST['venta'])){
    $comprobarApertura = new Caja;
    $comprobarApertura = $comprobarApertura->comprobar();
    if($comprobarApertura->estado == 'cerrado') {
        header('Location: /menu.php?r=6');
    } else {
        header('Location: /venta.php');
    }
}


if(isset($_POST['apertura'])) {
    $comprobarApertura = new Caja;
    $comprobarApertura = $comprobarApertura->comprobar();
    if($comprobarApertura->estado == 'abierto') {
        header('Location: /menu.php?r=4');
    } else {
        $abrirTotal = new Totales;
        $abrirTotal = $abrirTotal->comprobar();
        $abrirTotal->estado = 'abierto';
        $abrirTotal = $abrirTotal->cerrarAbrir();
        $ext = null;
        $caja->apertura = $_POST['apertura'];
        $errores = $caja->validar($ext);
        if (empty($errores)) {
            $caja = $caja->crearCaja();
        } else {
            $errores = Caja::getErrores();
        }
    }
    
}


?>
        <?php
            
            $mensaje = mostrarNotificaciones(intval($r));
        
        if($mensaje) { ?>
            <p class="alerta error"><?php echo s($mensaje); ?></p>
        <?php }  ?>  
        <?php foreach($errores as $error) { ?>
        <p class="alerta error"><?php echo $error ?></p>
        <?php } ?>
    <main class="contenedor seccion">
        <div class="menu">
            <div class="menu-dividido">
                <section class="menu-ajustes">
                    <?php if(!$empresa) {?>
                        <a href="/datosempresa.php" class="iconos-ajustes"><img src="/build/img/iconos/home.png" alt="Icono Home">Datos de la empresa</a>
                        <a href="/dispositivos.php" class="iconos-ajustes"><img src="/build/img/iconos/ajustes.png" alt="Icono Home">Configurar dispositivos</a>
                    <?php } else { ?>
                        <a href="/datosempresa.php?id=<?php echo $empresa[0]->id ?>" class="iconos-ajustes"><img src="/build/img/iconos/home.png" alt="Icono Home">Datos de la empresa</a>
                        <a href="/dispositivos.php?id=<?php echo $empresa[0]->id ?>" class="iconos-ajustes"><img src="/build/img/iconos/ajustes.png" alt="Icono Home">Configurar dispositivos</a>
                    <?php } ?>
                    <a href="/admin/usuarios.php" class="iconos-ajustes"><img src="/build/img/iconos/usuarios.png" alt="Icono Home">Gestionar usuarios</a>
                </section>
                <section class="menu-opciones">
                    <a href="/admin/articulos/articulos.php" class="iconos-opciones"><img src="/build/img/iconos/etiqueta.png" alt="Icono Home">Editar y crear articulos</a>
                    <a href="/clientes.php" class="iconos-opciones"><img src="/build/img/iconos/clientes.png" alt="Icono Home">Clientes</a>
                    <a href="/informesCaja.php" class="iconos-opciones"><img src="/build/img/iconos/cajaregistradora.png" alt="Icono Home">Informes de caja</a>
                    <a href="/tickets.php" class="iconos-opciones"><img src="/build/img/iconos/tickets.png" alt="Icono Home">Tickets</a>
                    <a href="/informes.php" class="iconos-opciones"><img src="/build/img/iconos/informes.png" alt="Icono Home">Informes</a>
                </section>
            </div>
            <div>
                <section class="menu-principal">
                    <button id="btnNewCat" class="iconos-principal"><img src="/build/img/iconos/llave.png" alt="Icono Home">Apertura de caja</button>
                    <form method="POST">
                    <button class="iconos-principal-venta" name="venta" value="<?php echo s('venta') ?>" ><img src="/build/img/iconos/cajaregristro.png" alt="Icono Home">Iniciar venta</button>
                    </form>
                    <button id="btnNewArt" class="iconos-principal"><img src="/build/img/iconos/candado.png" alt="Icono Home">Cierre de caja</button>
                    <a href="/cerrar-sesion.php" class="iconos-principal"><img src="/build/img/iconos/salir.png" alt="Icono Home">Salir</a>
                </section>
            </div>
        </div>
         <!-- ventana modal -->
         <div class="abrirCat ventana-modal">
            <div class="modal">
                <button id="btnCerrar" class="boton-rojo">Cerrar</button>
                <div class="contenido-modal">
                    <div>
                        <form method="POST">
                            <div class="formulario-totales" >
                                <label>Importe Apertura:</label>
                                <input class="totales" name="apertura" type="number" step="0.01" min="0"  value="<?php echo s(number_format(0, 2)) ?>">
                            </div>
                                <div class="">
                                    <button class="abrir-caja" type="submit">Abrir Caja</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!--fin ventana modal -->
        <!-- ventana modal -->
        <div class="abrirArt ventana-modal">
            <div class="modal">
                <button id="btnCerrarArt" class="boton-rojo">Cerrar</button>
                <div class="contenido-modal">
                    <div>
                        <form method="POST">
                            <div class="formulario-totales" >
                                <label>Importe Apertura:</label>
                                <input class="totales" type="text" name="cierre[apertura]"  value="<?php echo s(str_replace(',', '',number_format($cierreCaja->apertura, 2))) ?>" readonly>

                                <label>Total Entradas:</label>
                                <input class="totales"  type="text" name="cierre[entradas]"  value="<?php echo s(str_replace(',', '',number_format($importeTotal, 2))) ?>" readonly>

                                <label>Total Caja:</label>
                                <input class="totales"  type="text" name="cierre[total]"  value="<?php echo s(str_replace(',', '',number_format($importeTotal + $cierreCaja->apertura, 2))) ?>" readonly>
                            </div>
                                <div class="">
                                    <button class="abrir-caja" type="submit">Cerrar Caja</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!--fin ventana modal -->
    </main>
<?php
incluirTemplates('footer');
?>