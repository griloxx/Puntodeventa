<?php

require './../includes/app.php';
incluirTemplates('header');

use App\Usuarios;

$usuarios = new Usuarios;
$errores = [];

$usuarios = Usuarios::all();
$r = $_GET['r'] ?? null;
//Validar con los get sean enteros
$r = filter_var($r, FILTER_VALIDATE_INT);
// Para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {
        if($id === 1) {
            header('Location: /menu.php');
            exit;
        }
        // compara lo que vamos a eliminar
        $usuario = Usuarios::find($id);
        $errores = Usuarios::getErrores();
        if(!$errores) {
            // Llamo al metodo eliminar
            $dir = "/admin/usuarios.php";
            $usuario->eliminar($dir);
        }
    }
}

?>
<main class="contenedor seccion sesion">
    <h1 class="texto-centrado">Gestion de Usuarios</h1>
    <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
        <?php } ?>
        <?php foreach($errores as $error) { ?>
        <p class="alerta error"><?php echo $error ?></p>
        <?php } ?>
    <div class="separar-botones">
        <a href="/menu.php" class="boton-volver">Volver</a>
        <a href="/admin/usuarios/crear.php" class="boton-naranja">Crear</a>
    </div>
    <table class="usuarios">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as $usuario) { 
            if($usuario->id !== '1') {    
            ?>
            <tr>
                <td class="td-imagen"><img class="imagen-tabla" src="/imagenes/<?php echo s($usuario->imagen)?>" alt="Imagen Perfil"></td>
                <td><?php echo $usuario->usuario?></td>
                <td><?php echo $usuario->nombre?></td>
                <td><?php echo $usuario->apellidos?></td>
                <td><?php echo $usuario->email?></td>
                <td class="opciones">
                    <form method="POST" id="<?php echo "form" . $usuario->id?>">
                        <input type="hidden" name="id" value="<?php echo s($usuario->id) ?>" class="boton-rojo guardar">
                        <input type="submit" value="Eliminar" class="boton-rojo guardar">
                    </form>
                    <a href="usuarios/actualizar.php?id=<?php echo $usuario->id ?>" class="boton-verde guardar">Actualizar</a>
                </td>
            </tr>
            <?php } }?>
        </tbody>
    </table>
</main>
<?php 
incluirTemplates('footer')
?>