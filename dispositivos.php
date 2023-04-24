<?php
require './includes/app.php';
incluirTemplates('header');

use App\Empresa;
use Mike42\Escpos\Printer;

$empresa = new Empresa;
// obtener impresoras en windows

$output = shell_exec('wmic printer get sharename') ?? null;
if($output !== null) {
    $impresoras = explode("\n", $output);
} else {
    $impresoras = null;
}


//validar por url que sea un id valido
//dentro de un if($_GET) para que si es la primera vez y no hay datos creados de empresa como tal los cree
if($_GET) {
    $id = $_GET['id'] ?? null;
    $r = $_GET['r'] ?? null;
    //Validar con los get sean enteros
    $id = filter_var($id, FILTER_VALIDATE_INT);
    $r = filter_var($r, FILTER_VALIDATE_INT);
    // traer los datos al formulario para verlos y/o actualizar campos
    $empresa = Empresa::find($id);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // asignar los atributos para sincronizarlos a los actuales segun el id
    $args = $_POST['empresa'];
    $empresa->sincronizar($args);
    //metodo guardar que crea o actuliza en la base de datos
    $dir = "/dispositivos.php";
    $empresa->guardar($dir);
}


?>
    <main class="contenedor seccion">
        <h1 class="texto-centrado">Configurar Dispositivos</h1>
        <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
        <?php } ?>
        <div class="Sesion">
            <a href="menu.php" class="boton-volver">Volver</a>
            <form method="POST" class="formulario-ticket">
                <fieldset class="int-formulario">
                    <div class="datos">
                        <h2>Cabecera</h2>
                        <label for="logotipo">Logotipo:</label>
                        <input type="text" name="empresa[nombre]" placeholder="Texto Logo" id="logotipo" value="<?php echo s($empresa->nombre);?>" required>

                        <label for="nombre comercial">Nombre Comercial:</label>
                        <input type="text" name="empresa[nombreComercial]" placeholder="Nombre Conpumercial" id="nombreComercial" value="<?php echo s($empresa->nombreComercial);?>" required>

                        <label for="telefono">Telefono:</label>
                        <input type="text" name="empresa[telefono]" placeholder="Telefono" id="telefono" value="<?php echo s($empresa->telefono);?>" required>

                        <label for="email">Email:</label>
                        <input type="email" name="empresa[email]" placeholder="Email Tienda" id="email" value="<?php echo s($empresa->email);?>" required>

                        <label for="nif">NIF/CIF:</label>
                        <input type="text" name="empresa[nif]" placeholder="NIF/CIF" id="nif" value="<?php echo s($empresa->nif);?>" required>
                        
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="empresa[direccion]" placeholder="Dirección" id="direccion" value="<?php echo s($empresa->direccion);?>" required>

                        <label for="cp">CP, Poblacion, Provincia:</label>
                        <input type="text" name="empresa[cpPoblacion]" placeholder="CP, Poblacion, Provincia" id="cp" value="<?php echo s($empresa->cpPoblacion);?>" required>
                        
                        <h2>Pie</h2>
                        <label for="linea1">Linea 1:</label>
                        <input type="linea1" name="empresa[linea1]" placeholder="Linea 1" id="linea1" value="<?php echo s($empresa->linea1);?>" required>

                        <label for="linea2">Linea 2:</label>
                        <input type="linea2" name="empresa[linea2]" placeholder="Linea 2" id="linea2" value="<?php echo s($empresa->linea2);?>" required>

                        <label for="linea3">Linea 3:</label>
                        <input type="linea3" name="empresa[linea3]" placeholder="Linea 3" id="linea3" value="<?php echo s($empresa->linea3);?>" required>
                        <h2>Impresora</h2>
                        <select name="empresa[impresora]">
                            <option selected value="">--Seleccione--</option>
                            <?php foreach ($impresoras as $impresora) { 
                                $impresora = trim($impresora);
                                if ($impresora != '' && $impresora != 'Name') { ?>
                                    <option value="<?php echo s($impresora); ?>"><?php echo $impresora; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="ticket">
                        <div class="fondo-ticket">
                        <p><?php echo $empresa->nombre?></p>
                        <p><?php echo $empresa->nombreComercial?></p>
                        <p>Tel: <?php echo $empresa->telefono?></p>
                        <p><?php echo $empresa->email?></p>
                        <p><?php echo $empresa->nif?></p>
                        <p><?php echo $empresa->direccion?></p>
                        <p><?php echo $empresa->cpPoblacion?></p>
                        <p>-----------------------------</p>
                        <?php date_default_timezone_set('Europe/Madrid')?>
                        <p>Ticket: 101200    <?php echo date('d/m/Y H:i')?></p>
                        <p>-----------------------------</p>
                        <p>Cantidad  Articulo Precio Total</p>
                        <p>2 Trajes bonitos 3,00€ 6,00€</p>
                        <p>2 Trajes bonitos 3,00€ 6,00€</p>
                        <p>2 Trajes bonitos 3,00€ 6,00€</p>
                        <p>-----------------------------</p>
                        <p>TOTAL 28€</p>
                        <P>Entregado 30€</P>
                        <p>Cambio 2€</p>
                        <p>Metodo de pago: Efectivo</p>
                        <p>-----------------------------</p>
                        <p>IVA 21% 5€</p>
                        <p>-----------------------------</p>
                        <p><?php echo $empresa->linea1?></p>
                        <p><?php echo $empresa->linea2?></p>
                        <p><?php echo $empresa->linea3?></p>
                        </div>
                    </div>
                </fieldset>
                <div class="boton-derecha">
                    <input type="submit" value="Guardar" class="boton-azul guardar">
                    <a href="#" id="btnImprimir" class="boton-azul guardar">Imprimir</a>
                </div>
            </form>
        </div>
    </main>
<?php
incluirTemplates('footer');
?>