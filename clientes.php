<?php
require './includes/app.php';
incluirTemplates('header');


use App\clientes;
$clientes = new clientes;
$clientes = $clientes->all();


$r = $_GET['r'] ?? null;
//Validar con los get sean enteros
$r = filter_var($r, FILTER_VALIDATE_INT);
// traer los datos al formulario para verlos y/o actualizar campos


if(isset($_POST['cliente'])) {
    $args = $_POST['cliente'];
    $guardar = new clientes;
    $guardar->sincronizar($args);
    $dir = "/clientes.php";
    $guardar->guardar($dir);
}
if(isset($_POST['eliminar'])) {
    $eliminar = new clientes;
    $eliminar->id = $_POST['eliminar'];
    $dir = "/clientes.php";
    $eliminar = $eliminar->eliminar($dir);
}



?>
<main class="contenedor seccion sesion">
        <?php foreach($clientes as $cliente) { ?>
            <!-- ventana modal -->
            <div class="editarCat<?php echo $cliente->id?> ventana-modal">
                <div class="modal3">
                    <button id="<?php echo $cliente->id ?>" class="boton-rojo cerrarEditarCat<?php echo $cliente->id?>">Cerrar</button>
                    <div class="contenido-modal3">
                        <form method="POST" class="formulario-datos2">
                            <fieldset class="int-formulario2">
                                <div class="datos">
                                    <label for="nif">NIF/CIF:</label>
                                    <input type="text" name="cliente[nif]" placeholder="Introduce un NIF/CIF" id="nif" maxlength="20" value="<?php echo s($cliente->nif); ?>" required>

                                    <label for="nombreEmpresa">Nombre Empresa:</label>
                                    <input type="text" name="cliente[nombreEmpresa]" placeholder="Nombre Empresa" id="nombreEmpresa" maxlength="50" value="<?php echo s($cliente->nombreEmpresa); ?>">

                                    <label for="nombre">Nombre:</label>
                                    <input type="text" name="cliente[nombre]" placeholder="Nombre" id="nombre" maxlength="20" value="<?php echo s($cliente->nombre); ?>" required>

                                    <label for="apellidos">Apellidos:</label>
                                    <input type="text" name="cliente[apellidos]" placeholder="Apellidos" id="apellidos" maxlength="50" value="<?php echo s($cliente->apellidos); ?>">

                                    <label for="direccion">Dirección:</label>
                                    <input type="text" name="cliente[direccion]" placeholder="Dirección" id="direccion" maxlength="100" value="<?php echo s($cliente->direccion); ?>">

                                    <label for="poblacion">Poblacion, Provincia:</label>
                                    <input type="text" name="cliente[poblacionProvincia]" placeholder="Poblacion y Provincia" id="poblacion" maxlength="70" value="<?php echo s($cliente->poblacionProvincia); ?>">

                                    <label for="cp">Cp:</label>
                                    <input type="text" name="cliente[cp]" placeholder="Codigo postal" id="cp" maxlength="10" value="<?php echo s($cliente->cp); ?>">

                                    <label for="email">Email:</label>
                                    <input type="email" name="cliente[email]" placeholder="Email" id="email" maxlength="70" value="<?php echo s($cliente->email); ?>" >
                                    
                                    <label for="telefono">Telefono:</label>
                                    <input type="number" name="cliente[telefono]" placeholder="Telefono" id="telefono" value="<?php echo s($cliente->telefono); ?>" >
                                </div>
                            </fieldset>
                            <div class="boton-derecha">
                                <input type="hidden" name="cliente[id]" value="<?php echo s($cliente->id); ?>" class="boton-azul guardar">
                                <input type="submit" value="Guardar" class="boton-azul guardar">
                            </div>
                        </form> 
                    </div>
                </div>
            </div> <!--fin ventana modal -->
        <?php } ?>
    <div >
        <div>
        <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if($mensaje == 3) { ?>
            <p class="alerta error"><?php echo s($mensaje); ?></p>
        <?php } else if ($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
            <?php } ?>
            <h1 class="texto-centrado">Clientes</h1>
            <div class="separar-botones">
                <a href="/menu.php" class="boton-volver">Volver</a>
                <button id="btnNewCat" class="boton-naranja">Crear</button>
            </div>
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
                                <button id="<?php echo $cliente->id ?>" name="eliminar" value="<?php echo $cliente->id?>" class="boton-rojo clientes <?php echo $cliente->id?>">Eliminar</button>
                            </form>
                            <button id="<?php echo $cliente->id ?>" class="boton-verde clientes abrirEditarCat<?php echo $cliente->id?>">Editar</button>
                        </td>
                    </tr>
                        <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- ventana modal -->
    <div class="abrirCat ventana-modal">
        <div class="modal3">
            <button id="btnCerrar"  class="boton-rojo">Cerrar</button>
            <div class="contenido-modal3">
                <form method="POST" class="formulario-datos2">
                    <fieldset class="int-formulario2">
                        <div class="datos">
                            <label for="nif">NIF/CIF:</label>
                            <input type="text" name="cliente[nif]" placeholder="Introduce un NIF/CIF" id="nif" maxlength="20" value="" required>

                            <label for="nombreEmpresa">Nombre Empresa:</label>
                            <input type="text" name="cliente[nombreEmpresa]" placeholder="Nombre Empresa" id="nombreEmpresa" maxlength="50" value="">

                            <label for="nombre">Nombre:</label>
                            <input type="text" name="cliente[nombre]" placeholder="Nombre" id="nombre" maxlength="20" value="" required>

                            <label for="apellidos">Apellidos:</label>
                            <input type="text" name="cliente[apellidos]" placeholder="Apellidos" id="apellidos" maxlength="50" value="">

                            <label for="direccion">Dirección:</label>
                            <input type="text" name="cliente[direccion]" placeholder="Dirección" id="direccion" maxlength="100" value="">

                            <label for="poblacion">Poblacion, Provincia:</label>
                            <input type="text" name="cliente[poblacionProvincia]" placeholder="poblacion y provincia" id="poblacion" maxlength="70" value="">

                            <label for="cp">Cp:</label>
                            <input type="text" name="cliente[cp]" placeholder="Codigo postal" id="cp" maxlength="10" value="">

                            <label for="email">Email:</label>
                            <input type="email" name="cliente[email]" placeholder="Email" id="email" maxlength="70" value="" >
                            
                            <label for="telefono">Telefono:</label>
                            <input type="number" name="cliente[telefono]" placeholder="Telefono" id="telefono" value="" >
                        </div>
                    </fieldset>
                    <div class="boton-derecha">
                        <input type="submit" value="Guardar" class="boton-azul guardar">
                    </div>
                </form> 
            </div>
        </div>
    </div> <!--fin ventana modal -->
</main>
<?php
incluirTemplates('footer');
?>