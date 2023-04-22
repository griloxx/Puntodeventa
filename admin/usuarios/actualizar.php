<?php

require '../../includes/app.php';
incluirTemplates('header');

use App\Usuarios;
$usuarios = new Usuarios;

//validar por url que sea un id valido
$id = $_GET['id'] ?? null;
$r = $_GET['r'] ?? null;
//Validar con los get sean enteros
$id = filter_var($id, FILTER_VALIDATE_INT);
$r = filter_var($r, FILTER_VALIDATE_INT);
// traer los datos al formulario para verlos y/o actualizar campos
$usuarios = Usuarios::find($id);




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // asignar los atributos para sincronizarlos a los actuales segun el id
    $args = $_POST['usuarios'];
    // si el campo del formulario esta vacio coge la misma para que no la vuelva a hashear
    if(!$args['password']) {
        $args['password'] = $usuarios->password;
    } else if($args['password'] != $usuarios->password) { // si hay contraseña nueva entonces la hashea para enviarla sincronizada ya hasheada
        $args['password'] = password_hash($args['password'], PASSWORD_DEFAULT);
    }
    
    $usuarios->sincronizar($args);
    
    //nombre imagen
    $imagen = $_FILES['usuarios']['name']['imagen'];
    $imagenTemporal = $_FILES['usuarios']['tmp_name']['imagen'];
    $ext = pathinfo($imagen, PATHINFO_EXTENSION);
    //generar nombre aleatorio para subida de imagenes dentro del if para que lo genere solo si hay imagen y no de errores
    if($imagen) {
        // generar un nombre unico para cada imagen y que no se reemplace
        $nombreImagen = 'perfil' . $id . '.' . $ext;
    }
    // setear la imagen
    $usuarios->setImagen($nombreImagen);
    // is_dir comprueba si existe o no la carpeta
    if(!is_dir(CARPETA_IMAGENES)) {
        mkdir(CARPETA_IMAGENES);
    }
    // guarda la imagen en el servidor
    $rutaImagen = CARPETA_IMAGENES . $nombreImagen;
    // Mueve el archivo de la carpeta temporal a la ruta definida
    move_uploaded_file($imagenTemporal, $rutaImagen);
    //metodo guardar que crea o actuliza en la base de datos
    $dir = "/admin/usuarios.php";
    $usuarios->guardar($dir);
}

?>
    <main class="contenedor seccion">
        <h1 class="texto-centrado">Datos de Usuario</h1>
        <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
        <?php } ?>
        <div class="Sesion">
            <a href="../usuarios.php" class="boton-volver">Volver</a>
            <form method="POST" class="formulario-datos" enctype="multipart/form-data">
                <fieldset class="int-formulario">
                    <div class="datos">
                        <label for="email">Correo:</label>
                        <input type="email" name="usuarios[email]" placeholder="Introduce un correo" id="email" value="<?php echo s($usuarios->email); ?>" required>

                        <label for="password">Contraseña:</label>
                        <input type="password" name="usuarios[password]" placeholder="Introduce una contraseña" id="password" value="">

                        <label for="usuario">Usuario:</label>
                        <input type="text" name="usuarios[usuario]" placeholder="Nombre Usuario" id="usuario" value="<?php echo s($usuarios->usuario); ?>" required>

                        <label for="nombre">Nombre:</label>
                        <input type="text" name="usuarios[nombre]" placeholder="Nombre" id="nombre" value="<?php echo s($usuarios->nombre); ?>" required>

                        <label for="apellidos">Apellidos:</label>
                        <input type="text" name="usuarios[apellidos]" placeholder="Apellidos" id="apellidos" value="<?php echo s($usuarios->apellidos); ?>" required>
                    </div>
                    <fieldset class="subir-logo">
                        <label for="imagen">Imagen Perfil:</label>
                        <input type="file" id="imagen" name="usuarios[imagen]" accept="image/jpeg">
                    </fieldset>
                </fieldset>
                <div class="boton-derecha">
                    <input type="submit" value="Guardar" class="boton-azul guardar">
                </div>
            </form> 
        </div>
    </main>
<?php
incluirTemplates('footer');
?>