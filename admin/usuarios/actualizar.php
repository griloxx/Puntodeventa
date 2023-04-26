<?php

require '../../includes/app.php';
incluirTemplates('header');

use App\Empresa;
use App\Usuarios;
$usuarios = new Usuarios;
$errores = [];


//validar por url que sea un id valido
$id = $_GET['id'] ?? null;
$r = $_GET['r'] ?? null;
//Validar con los get sean enteros
$id = filter_var($id, FILTER_VALIDATE_INT);
$r = filter_var($r, FILTER_VALIDATE_INT);
// traer los datos al formulario para verlos y/o actualizar campos
if($id === 1 ){
    header('Location: /menu.php');
}
$usuarios = Usuarios::find($id);




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // asignar los atributos para sincronizarlos a los actuales segun el id
    $args = $_POST['usuarios'];
    
    // no mando llamar sincronizar para que no coga la misma contraseña ya hasheada
    $usuarios->email = $args['email'];
    $usuarios->usuario = $args['usuario'];
    $usuarios->nombre = $args['nombre'];
    $usuarios->apellidos = $args['apellidos'];
    if(!empty($args['password'])) {
        $usuarios->password = $args['password'];
        $comprobar = $usuarios->validarContraseña(); // para que no me la compruebe si esta vacio ya que esta hasheada y es mas de 15 caracteres
    }
    
    //nombre imagen
    $imagen = $_FILES['usuarios']['name']['imagen'];
    $imagenTemporal = $_FILES['usuarios']['tmp_name']['imagen'];
    $ext = pathinfo($imagen, PATHINFO_EXTENSION);
    //generar nombre aleatorio para subida de imagenes dentro del if para que lo genere solo si hay imagen y no de errores
    if($imagen) {
        // generar un nombre unico para cada imagen y que no se reemplace
        $nombreImagen = 'perfil' . $id . '.' . $ext;
        // setear la imagen
        $usuarios->setImagen($nombreImagen);
    }
    if(!empty($args['password'])) {
        $validar = $usuarios->validarExtendido($ext);
        $errores = array_merge($comprobar, $validar);
    } else {
        $errores = $validar;
    }
    if(empty($errores)) {
        if(!empty($args['password'])) {
            $usuarios->password = password_hash($args['password'], PASSWORD_DEFAULT);
        }
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
    } else {
        $errores = Empresa::getErrores();
    }
}

?>
    <main class="contenedor seccion">
        <h1 class="texto-centrado">Datos de Usuario</h1>
        <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
        <?php } ?>
        <?php foreach($errores as $error) { ?>
        <p class="alerta error"><?php echo $error ?></p>
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