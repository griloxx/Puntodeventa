<?php
require './includes/app.php';
incluirTemplates('header');

use App\Empresa;
$empresa = new Empresa;
$errores = [];

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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // asignar los atributos para sincronizarlos a los actuales segun el id
    $args = $_POST['empresa'];
    $empresa->sincronizar($args);
    //nombre imagen
    $imagen = $_FILES['empresa']['name']['imagen'];
    $imagenTemporal = $_FILES['empresa']['tmp_name']['imagen'];
    $ext = pathinfo($imagen, PATHINFO_EXTENSION);

    //generar nombre aleatorio para subida de imagenes dentro del if para que lo genere solo si hay imagen y no de errores
    if($imagen) {
        // generar un nombre unico para cada imagen y que no se reemplace
        $nombreImagen = 'logo.' . $ext;
    }
    $errores = $empresa->validar($ext);
    if(empty($errores)) {
        // setear la imagen
        $empresa->setImagen($nombreImagen);
        // is_dir comprueba si existe o no la carpeta
        if(!is_dir(CARPETA_IMAGENES)) {
            mkdir(CARPETA_IMAGENES);
        }
        // guarda la imagen en el servidor
        $rutaImagen = CARPETA_IMAGENES . $nombreImagen;
        // Mueve el archivo de la carpeta temporal a la ruta definida
        move_uploaded_file($imagenTemporal, $rutaImagen);
        //metodo guardar que crea o actuliza en la base de datos
        $dir = "/datosempresa.php";
        $empresa->guardar($dir);
    } else {
        $errores = Empresa::getErrores();
    }
    
}

?>
    <main class="contenedor seccion">
        <h1 class="texto-centrado">Datos de la Empresa</h1>
        <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
        <?php } ?>
        <?php foreach($errores as $error) { ?>
        <p class="alerta error"><?php echo $error ?></p>
        <?php } ?>
        <div class="Sesion">
            <a href="menu.php" class="boton-volver">Volver</a>
            <form method="POST" class="formulario-datos" enctype="multipart/form-data">
                <fieldset class="int-formulario">
                    <div class="datos">
                        <label for="nif">NIF/CIF:</label>
                        <input type="text" name="empresa[nif]" placeholder="Introduce un NIF/CIF" id="nif" maxlength="11" value="<?php echo s($empresa->nif); ?>" required>

                        <label for="nombre comercial">Nombre Comercial:</label>
                        <input type="text" name="empresa[nombreComercial]" placeholder="Nombre Comercial" id="nombreComercial" maxlength="20" value="<?php echo s($empresa->nombreComercial); ?>" required>

                        <label for="nombre">Nombre:</label>
                        <input type="text" name="empresa[nombre]" placeholder="Nombre" id="nombre" maxlength="20" value="<?php echo s($empresa->nombre); ?>" required>

                        <label for="direccion">Dirección:</label>
                        <input type="text" name="empresa[direccion]" placeholder="Dirección" id="direccion" maxlength="100" value="<?php echo s($empresa->direccion); ?>" required>

                        <label for="poblacion">Poblacion:</label>
                        <input type="text" name="empresa[poblacion]" placeholder="Poblacion" id="poblacion" maxlength="20" value="<?php echo s($empresa->poblacion); ?>" required>

                        <label for="cp">CP:</label>
                        <input type="text" name="empresa[cp]" placeholder="Codigo Postal" id="cp" maxlength="6" value="<?php echo s($empresa->cp); ?>" required>
                        
                        <label for="email">Email:</label>
                        <input type="email" name="empresa[email]" placeholder="Email Tienda" id="email" maxlength="60" value="<?php echo s($empresa->email); ?>" required>
                        
                        <label for="telefono">Telefono:</label>
                        <input type="telefono" name="empresa[telefono]" placeholder="Telefono Tienda" id="telefono" maxlength="20" value="<?php echo s($empresa->telefono); ?>" required>
                    </div>
                    <fieldset class="subir-logo">
                        <label for="imagen">Logo Tienda:</label>
                        <input type="file" id="imagen" name="empresa[imagen]" accept="image/png">
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