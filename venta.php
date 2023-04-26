<?php

require './includes/app.php';
incluirTemplates('headerventa');
use App\Categorias;
use App\Articulos;
use App\Ticket;
use App\Totales;
use App\Tregalo;
use App\Clientes;

$categorias = new Categorias;
$articulos = new Articulos;
$ticket = new Ticket;
$clientes = new Clientes;
$totalCliente = new Totales;
$errores = [];

$totalCliente = $totalCliente->comprobar();
$categorias = Categorias::all();
$clientes = $clientes->all();


$mostrarTicket = new Ticket;
$mostrarTicket = $mostrarTicket->comprobar();
$id = $mostrarTicket->ticketsId;
$mostrarTicket = $mostrarTicket->mostrarTicket($id);

$mensajeCodigo = "Nombre o codigo no encontrado";

$r = $_GET['r'] ?? null;

if(isset($_GET['r'])) {
    $r = filter_var($r, FILTER_VALIDATE_INT);
    $mostrarTarjeta = new Tregalo;
    $mostrarTarjeta = $mostrarTarjeta->comprobar();
    $idTarjeta = $mostrarTarjeta->id;
}



if(isset($_GET['cat'])){
    $cat = $_GET['cat'];
    $cat = filter_var($cat, FILTER_VALIDATE_INT);
    $articulosCat = Articulos::where($cat);
}

if(isset($_POST['cliente'])) {
    $args = $_POST['cliente'];
    $guardar = new Clientes;
    $guardar->sincronizar($args);
    $errores = $guardar->validar($ext = null);
    if(!$errores) {
        $dir = "/venta.php";
        $guardar->guardar($dir);
    } else {
        $errores = Clientes::getErrores();
    }
}

if(isset($_POST['clientes']['seleccionar'])) {
    $id = $_POST['clientes']['seleccionar'];
    $selec = new Totales;
    $selec = $selec->comprobar();
    $selec->cliente = $id;
    $errores = $selec->validar($ext = null);
    if(empty($errores)) {
        $selec = $selec->actualizar7();
    } else {
        $errores = Totales::getErrores();
    }
}

if(isset($_POST['art'])){
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $base = $_POST['base'];
    $art = $_POST['art'];
    $art = filter_var($art, FILTER_VALIDATE_INT);
    // Obtenemos la lista de artículos del ticket
    $listadoArticulos = new Ticket;
    $listadoArticulos = $listadoArticulos->comprobar();
    $id = $listadoArticulos->ticketsId;
    $idListado = $listadoArticulos->id;
    $listadoArticulos = $listadoArticulos->mostrarTicket($id);
    $prevenir = new Ticket;
    $prevenir->sincronizar($_POST);
    $prevenir->id = $art;
    $errores = $prevenir->validarArticulo();
    if(empty($errores)) {

        $stock = new Articulos;
        $stock = $stock->find($art);
        $stock->stock--;
        $stock = $stock->actualizarStock();
        
        
        // buscar si el articulo ya esta en la lista
        $encontrado = false;
        foreach($listadoArticulos as &$listado) {
            if($listado->codigo == $codigo) {
                $listado->cantidad++;
                $listado->base = $base;
                $listado->total = $listado->precio * $listado->cantidad;
                $encontrado = true;
                $crearArticulo = new ticket;
                $crearArticulo->actualizar2($listado);
                break;
            }
        }
        
        // Si el artículo no estaba en la lista, lo agregamos
        if(!$encontrado) {
            $listado = new Ticket;
            $listado = $listado->comprobarId();
            $listado->codigo = $codigo;
            $listado->nombre = $nombre;
            $listado->base = $base;
            $listado->precio = $precio;
            $listado->ticketsId = $id;
            $listado->cantidad = 1;
            $listado->total = $precio;
            $listadoArticulos[] = $listado;
            $crearArticulo = new ticket;
            $crearArticulo->actualizar1($listado);
        }
    } else {
        $errores = Ticket::getErrores();
    }
}
if(isset($_POST['buscar'])) {
    $nombre = $_POST['buscar'];
    if ($_POST['cantidad'] !== "") {
        $cantidad = $_POST['cantidad'];
        if(strlen($cantidad) > 8) {
            $errores[] = 'Introduce una cantidad mas pequeña';
        }
        } else  {
            $cantidad = 1;
        }
        if(!$errores) {
            $resultadoBusqueda = new Articulos;
            $resultadoBusqueda = $resultadoBusqueda->buscar($nombre);
            $resultadoBusqueda = array_shift($resultadoBusqueda);
        
            if($nombre != $resultadoBusqueda->nombre ) {
                header('Location: /venta.php?m=1');
            }
            $listadoArticulos = new Ticket;
            $listadoArticulos = $listadoArticulos->comprobar();
            $id = $listadoArticulos->ticketsId;
            $idListado = $listadoArticulos->id;
            $listadoArticulos = $listadoArticulos->mostrarTicket($id);
            $art = $resultadoBusqueda->id;
            $stock = new Articulos;
            $stock = $stock->find($art);
            $base = $stock->base;
            $stock->stock = $stock->stock - $cantidad;
            $stock = $stock->actualizarStock();
            // buscar si el articulo ya esta en la lista
            $encontrado = false;
            foreach($listadoArticulos as &$listado) {
                if($listado->nombre == $nombre || $listado->codigo == $nombre) {
                    $listado->cantidad = $listado->cantidad + $cantidad;
                    $listado->base = $base;
                    $listado->total = $listado->precio * $listado->cantidad;
                    $encontrado = true;
                    $crearArticulo = new ticket;
                    $crearArticulo->actualizar2($listado);
                    break;
                }
            }
            // Si el artículo no estaba en la lista, lo agregamos
            if(!$encontrado) {
                $listado = new Articulos;
                $listado = $listado->busqueda($nombre);
                $crearArticulo = new ticket;
                $crearArticulo = $crearArticulo->comprobarId();
                $crearArticulo->codigo = $listado->codigo;
                $crearArticulo->nombre = $listado->nombre;
                $crearArticulo->precio = $listado->pvp;
                $crearArticulo->base = $listado->base;
                $crearArticulo->cantidad = $cantidad;
                $crearArticulo->total = $crearArticulo->precio * $crearArticulo->cantidad;
                $crearArticulo->ticketsId = $id;
                $crearArticulo->actualizar1($crearArticulo);
            }
        }
    
}



if(isset($_POST['cobrar'])){
    $cobros = $_POST['cobrar'];
    if($cobros['total'] < 0) {
        $tRegalo = new Tregalo;
        $tRegalo->importe = $cobros['total'];
        $tRegalo = $tRegalo->crearTarjeta();
    }
    $totalCobrar = new Totales;
        $totalCobrar = $totalCobrar->comprobar();
        date_default_timezone_set("Europe/Madrid");
        $totalCobrar->fecha = date("Y-m-d");
        $totalCobrar->sub = $cobros['sub'];
        if($cobros['tRegalo']) {
            $totalCobrar->tRegalo = $cobros['tRegalo'];
            $cerrarTarjeta = new Tregalo;
            $cerrarTarjeta->id = $cobros['tRegalo'];
            $cerrarTarjeta->importe = $cobros['tRegaloImporte'];
            $cerrarTarjeta->estado = 'cerrado';
            $cerrarTarjeta = $cerrarTarjeta->cerrarAbrir();
        } else {
            $totalCobrar->tRegalo = '0';
        }
        $totalCobrar->total = $cobros['total'];
        $totalCobrar->entregado = $cobros['entregado'];
        $totalCobrar->cambio = $cobros['devolver'];
        $totalCobrar->metodo = $cobros['metodo'];
        if($cobros['total'] < 0) {
            $totalCobrar = $totalCobrar->actualizar6();
        } else {
            $totalCobrar = $totalCobrar->actualizar5();
        }
}
if(isset($_POST['newPrecio'])){
    $newPrecio = $_POST['newPrecio'];
    $id = $newPrecio['id'];
    $actualizar = new Ticket;
    $actualizar->id = $id;
    $actualizar->precio = $newPrecio['precio'];
    $errores = $actualizar->validar($ext = null);
    if(!$errores) {
        $actualizar = $actualizar->find($id);
        $actualizar->precio = $newPrecio['precio'];
        $iva = 21.00;
        $base = $actualizar->precio / (1 + $iva/100);
        $base = number_format($base,2);
        $actualizar->base = $base;
        $actualizar->total = $actualizar->precio * $actualizar->cantidad;
        $actualizar->total = number_format($actualizar->total,2);
        $actualizar->actualizar2($actualizar);
    } else {
        $errores = Articulos::getErrores();
    }
    
}

if(isset($_POST['eliminar']['id'])) {
    $id = $_POST['eliminar']['id'];
    $nombre = $_POST['eliminar']['nombre'];
    $cantidad = $_POST['eliminar']['cantidad'];
    $stock = new Articulos;
    $stock->id = $id;
    $errores = $stock->validarEliminar();
    if(!$errores) {
        $stock = $stock->findNombre($nombre);
        $stock->stock = $stock->stock + $cantidad;
        $stock = $stock->actualizarStock();
        $eliminarArt = new ticket;
        $eliminarArt->id = $id;
        $eliminarArt = $eliminarArt->eliminar1();
    } else {
        $errores = Articulos::getErrores();
    }
}


?>

<div class="contenedor seccion dividir">
    <main class="menu-ventana">
            <a href="/menu.php" class="boton-volver">Volver</a>
            <?php if(isset($_GET['m'])) { ?>
              <p class="alerta error"><?php echo $mensajeCodigo ?></p> 
            <?php } ?>
            <?php
            $mensaje = mostrarNotificaciones(intval($r));
            if($r === 7) { ?>
                <p class="alerta insertado"><?php echo s($mensaje) . $idTarjeta; ?></p>
            <?php } else if ($r === 12 ){ ?>
                <p class="alerta error"><?php echo s($mensaje) ?></p>
            <?php } ?> 
            <?php foreach($errores as $error) { ?>
            <p class="alerta error"><?php echo $error ?></p>
            <?php } ?>
        <div class="ventana-cobro">
            <!-- Pantalla cobro -->
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
                            <td><button class="cambiar-precio abrirModalPrecio<?php echo $mostrar->id ?>" id="<?php echo s($mostrar->id) ?>""><?php echo $mostrar->precio ?></button></td>
                            <td><?php echo $mostrar->cantidad ?></td>
                            <td><?php echo $mostrar->total ?></td>
                            <form method="POST">
                            <td><button type="submit" name="eliminar[id]" value="<?php echo s($mostrar->id) ?>" class="boton-rojo"><img class="icono" src="/build/img/iconos/icons8-papelera-llena-100.png" alt="Icono papelera"></button><input type="hidden" name="eliminar[cantidad]" value="<?php echo $mostrar->cantidad ?>"><input type="hidden" name="eliminar[nombre]" value="<?php echo $mostrar->nombre ?>"></td>
                            </form>
                        </tr>
                         <!-- ventana modal -->
                        <div class="modalPrecio<?php echo $mostrar->id ?> ventana-modal">
                            <div class="modal4">
                                <button class="cerrarModalPrecio<?php echo $mostrar->id ?> boton-rojo" id="<?php echo $mostrar->id ?>">Cerrar</button>
                                <div class="contenido-modal4">
                                    <form method="POST" >
                                        <div class="separador">
                                            <div class="contenedor-azul">
                                                <label for="precio">Precio:</label>
                                                <input type="number" step="0.01" name="newPrecio[precio]" max="99999999" id="precio" value="<?php echo s($mostrar->precio) ?>" >
                                            </div>
                                            <input type="hidden" name="newPrecio[id]" value="<?php echo s($mostrar->id) ?>">
                                            <input type="submit" class="boton-volver" value="Guardar">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                         <!--fin ventana modal -->
                        <?php }
                     }?>
                    </tbody>
                </table>
                <form method="POST"">
                    <div class="form-totales" >
                        <label>Sub:</label>
                        <input class="totales" name="cobrar[sub]" type="text" maxlength="10" value="<?php echo s(number_format($subtotal, 2)) ?>" readonly>

                        <label>Total:</label>
                        <input class="totales" name="cobrar[total]" type="text" maxlength="10" value="<?php echo s(number_format($total, 2)) ?>" readonly>

                        <?php if($totalCliente->cliente !== '1') { ?>
                        <label>Cliente:</label>
                        <input class="totales" type="text" maxlength="8" value="<?php echo s($totalCliente->cliente) ?>" readonly>
                        <?php } ?>
                    </div>
                </form>
            </div>
            <!-- Termina Pantalla cobro -->
                <!-- ventana modal -->
            <div class="abrirCat ventana-modal">
                <div class="modal">
                    <button id=btnCerrar class="boton-rojo">Cerrar</button>
                    <div class="contenido-modal">
                        <div>
                            <form method="POST" id="mi-formulario">
                                <div class="formulario-totales" >
                                    <label>Sub:</label>
                                    <input class="totales" id="subCobro" name="cobrar[sub]" type="text" value="<?php echo s(number_format($subtotal, 2)) ?>" readonly>

                                    <label>Tarjeta Regalo Nº</label>
                                    <input type="text" id="busquedaTarjetas" class="totales"  name="cobrar[tRegalo]" list="regalos" placeholder="Código Tarjeta" value="<?php echo s($_POST['cobrar']['tRegalo'] ?? ''); ?>" autocomplete="off">

                                    <label>Importe Tarjeta Regalo:</label>
                                    <input class="totales tarjeta" id="importeTarjeta" name="cobrar[tRegaloImporte]" type="text" value="<?php echo s($_POST['cobrar']['tRegaloImporte'] ?? ''); ?>" oninput="calcularDescuento()" readonly>
                                    
                                    <label>Total:</label>
                                    <input class="totales tottal" id="precioTotal" name="cobrar[total]" type="text" value="<?php echo s(number_format($total, 2)) ?>" readonly>
                                    
                                    <label>Entregado:</label>
                                    <input class="totales" id="entregado" name="cobrar[entregado]" type="text" value="<?php echo s($_POST['cobrar']['entregado'] ?? ''); ?>" oninput="calcularDevolver()">

                                    <label>Devolver:</label>
                                    <input class="totales" id="devolver" name="cobrar[devolver]" type="text" value="<?php echo s($_POST['cobrar']['devolver'] ?? ''); ?>" readonly>

                                </div>
                                    <div class="ventana-menu-2">
                                        <button class="menu-cobrar pagar"   value="<?php echo s('tarjeta') ?>" type="submit"  >Tarjeta</button>
                                        <button class="menu-cobrar pagar"   value="<?php echo s('efectivo') ?>" type="submit"  >Efectivo</button>
                                        <input type="hidden" id="boton-pulsado" name="cobrar[metodo]" value="<?php echo s($_POST['cobrar']['metodo'] ?? ''); ?>">
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!--fin ventana modal -->
              <!-- ventana modal -->
              <div class="abrirArt ventana-modal">
                <div class="modal2">
                    <button id=btnCerrarArt class="boton-rojo">Cerrar</button>
                    <div class="contenido-modal2">
                            <button class="ultimo-ticket" id="ticketRegalo"  >Imprimir Ticket Regalo</button>
                            <button class="ultimo-ticket" id="ticket"  >Imprimir Ticket</button>
                    </div>
                </div>
            </div> <!--fin ventana modal -->
            <!-- ventana modal -->
            <div class="modalCliente ventana-modal">
                <div class="modal3">
                    <button id="cerrarCliente"  class="boton-rojo">Cerrar</button>
                    <div class="contenido-modal3">
                        <form method="POST" class="formulario-datos2">
                            <fieldset class="int-formulario2">
                                <div class="datos">
                                    <label for="nif">NIF/CIF:</label>
                                    <input type="text" name="cliente[nif]" placeholder="Introduce un NIF/CIF" id="nif" maxlength="20" value="<?php echo s($_POST['cliente']['nif'] ?? ''); ?>" required>

                                    <label for="nombreEmpresa">Nombre Empresa:</label>
                                    <input type="text" name="cliente[nombreEmpresa]" placeholder="Nombre Empresa" id="nombreEmpresa" maxlength="50" value="<?php echo s($_POST['cliente']['nombreEmpresa'] ?? ''); ?>">

                                    <label for="nombre">Nombre:</label>
                                    <input type="text" name="cliente[nombre]" placeholder="Nombre" id="nombre" maxlength="20" value="<?php echo s($_POST['cliente']['nombre'] ?? ''); ?>" required>

                                    <label for="apellidos">Apellidos:</label>
                                    <input type="text" name="cliente[apellidos]" placeholder="Apellidos" id="apellidos" maxlength="50" value="<?php echo s($_POST['cliente']['apellidos'] ?? ''); ?>">

                                    <label for="direccion">Dirección:</label>
                                    <input type="text" name="cliente[direccion]" placeholder="Dirección" id="direccion" maxlength="100" value="<?php echo s($_POST['cliente']['direccion'] ?? ''); ?>">

                                    <label for="poblacion">Poblacion, Provincia:</label>
                                    <input type="text" name="cliente[poblacionProvincia]" placeholder="Poblacion y Provincia" id="poblacion" maxlength="70" value="<?php echo s($_POST['cliente']['poblacionProvincia'] ?? ''); ?>">

                                    <label for="cp">Cp:</label>
                                    <input type="text" name="cliente[cp]" placeholder="Codigo Postal" id="cp" maxlength="10" value="<?php echo s($_POST['cliente']['cp'] ?? ''); ?>">

                                    <label for="email">Email:</label>
                                    <input type="email" name="cliente[email]" placeholder="Email" id="email" maxlength="70" value="<?php echo s($_POST['cliente']['email'] ?? ''); ?>" >
                                    
                                    <label for="telefono">Telefono:</label>
                                    <input type="number" name="cliente[telefono]" placeholder="Telefono" id="telefono" maxlength="9" value="<?php echo s($_POST['cliente']['telefono'] ?? ''); ?>" >
                                </div>
                            </fieldset>
                            <div class="boton-derecha">
                                <input type="submit" value="Guardar" class="boton-azul guardar">
                            </div>
                        </form> 
                    </div>
                </div>
            </div> <!--fin ventana modal -->
            <!-- ventana modal -->
            <div class="modalACliente ventana-modal">
                <div class="modal3">
                    <button id="cerrarACliente"  class="boton-rojo">Cerrar</button>
                    <div class="contenido-modal3">
                        <table class="usuarios">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Nif</th>
                                    <th>Telefono</th>
                                    <th>Nombre Empresa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($clientes as $cliente) { 
                                    if($cliente->id !== '1'){

                                ?>
                                <tr>
                                    <td><?php echo $cliente->id?></td>
                                    <td><?php echo $cliente->nombre?></td>
                                    <td><?php echo $cliente->apellidos?></td>
                                    <td><?php echo $cliente->nif?></td>
                                    <td><?php echo $cliente->telefono?></td>
                                    <td><?php echo $cliente->nombreEmpresa?></td>
                                    <td class="ultimo">
                                        <form method="POST" >
                                            <button  name="clientes[seleccionar]" value="<?php echo s(1)?>" class="boton-rojo clientes">Cancelar</button>
                                            <button  name="clientes[seleccionar]" value="<?php echo s($cliente->id)?>" class="boton-verde clientes">Selec.</button>
                                        </form>
                                    </td>
                                </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!--fin ventana modal -->
            <!-- Empieza teclado -->
            <div class="teclado">
                <form method="POST" class="buscador-form">
                    <div class="buscador">
                        <input type="text" id="busqueda" name="buscar" list="resultados" placeholder="Código o Nombre" value="<?php echo s($_POST['buscar'] ?? '') ?>" autocomplete="off">
                            <datalist id="resultados">
                            <option value="">
                        </datalist>
                    </div>
                    <form method="POST" >
                        <input type="text" name="cantidad" maxlength="8" value="<?php echo s($_POST['cantidad'] ?? ''); ?>" autocomplete="off">
                            <div class="teclas">
                            <button class="numerico">1</button>
                            <button class="numerico">2</button>
                            <button class="numerico">3</button>
                            <button class="numerico">4</button>
                            <button class="numerico">5</button>
                            <button class="numerico">6</button>
                            <button class="numerico">7</button>
                            <button class="numerico">8</button>
                            <button class="numerico">9</button>
                            <button class="numerico-borrar">ce</button>
                            <button class="numerico">0</button>
                            <button class="numerico">.</button>
                            <button class="numerico">-</button>
                            <button class="numerico-intro">intro</button>
                        </div>
                    </form>
                </form>
            </div>
            <!-- termina teclado -->
            <!-- Menú cobrar -->
        <div class="ventana-menu">
            <button id="btnNewArt" class="menu-cobrar">Ultimo Ticket</button>
            <button id="abrirACliente" class="menu-cobrar">Asignar a cliente</button>
            <button id="abrirCliente" class="menu-cobrar">Datos cliente</button>
            <button id="abrirCajon" class="menu-cobrar">Abrir cajón</button>
            <button id="btnNewCat" class="menu-cobrar" >Cobrar</button>
        </div>
        <!-- Menú cobrar -->
    </main>
    <!-- Categorias -->
    <section class="ventana-articulos">
        <div class="categorias-general">
            <div class="categorias-articulos">
                <div >
                    <form class="mostrarCategorias" method="GET"">
                <?php if(isset($categorias)) {
                    foreach($categorias as $categoria) {?>                  
                    <input type="hidden" class="cat" value="<?php echo s($categoria->id) ?>">
                    <button type="submit" class="boton-seleccion" onclick="addParamCat(event)"><?php echo $categoria->nombre ?></button>
                <?php } }?>
                    </form>
                </div>
                <!-- Articulos -->
                <div class="mostrarArticulos">
                    <?php if(isset($articulosCat)) {
                        foreach($articulosCat as $articulo) {?>
                    <form method="POST" >
                        <input type="hidden" class="art" name="precio" value="<?php echo s($articulo->pvp) ?>">
                        <input type="hidden" class="art" name="base" value="<?php echo s($articulo->base) ?>">
                        <input type="hidden" class="art" name="codigo" value="<?php echo s($articulo->codigo) ?>">
                        <input type="hidden" class="art" name="nombre" value="<?php echo s($articulo->nombre) ?>">
                        <input type="hidden" class="art" name="art" value="<?php echo s($articulo->id) ?>">
                        <button type="submit" class="boton-seleccion" onclick="addParamArt(event)"><?php echo $articulo->nombre ?></button>
                    </form>
                <?php } }?>
                </div>
                <!-- Termina articulos -->
            </div>
        </div>
    </section>
    <!-- Termina categorias -->
</div>
<script src="/jquery.js"></script>

<?php
incluirTemplates('footer');
?>