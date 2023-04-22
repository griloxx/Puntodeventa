<?php
require './includes/app.php';

use App\Usuarios;

$usuarios = new Usuarios;

session_start();

$errores = "";

if (isset($_SESSION['login'])) {
    return header('Location: /menu.php');
}

// autenticar al usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $args = $_POST['usuarios'];
    $usuarios->sincronizar($args);
    
    $usuario = $usuarios->autenticar();
    if($usuario) {
        $auth = password_verify($args['password'], $usuario->password);
        if ($auth) {
            // El usuario está autenticado
            session_start();
    
            // LLenar el arreglo de la sesion
            $_SESSION['usuario'] = $usuario->usuario;
            $_SESSION['imagen'] = $usuario->imagen;
            $_SESSION['login'] = true;
    
            header('Location: /menu.php');
        } else {
            $errores = "El password es incorrecto";
        }
    } else {
        $errores = "El usuario no existe";
    }
    
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="build/css/app.css">
    <title>Inicio Sesion</title>
</head>
<body>
    <header class="contenedor">
        <div class="logo">
                <img class="logo-login" loading="lazy" src="/imagenes/logo.png" alt="logo">
        </div>
    </header>
    <main class="contenedor contenido-main">
        <h1 class="texto-centrado">Iniciar Sesión</h1>
        <?php if($errores) { ?>
        <p class="alerta error"><?php echo $errores ?></p>
        <?php } ?>
        <div class="Sesion">
            <form method="POST" class="formulario">
                <fieldset class="int-formulario">
                
                    <label for="usuario">Usuario:</label>
                    <input type="text" name="usuarios[usuario]" placeholder="Tu Usuario" id="usuario" value="<?php echo s($usuarios->usuario); ?>" required>
                    
                    <label for="password">Contraseña:</label>
                    <input type="password" name="usuarios[password]" placeholder="Tu Contraseña" id="password" value="<?php echo s($usuarios->password); ?>" required>

                </fieldset>

                <input type="submit" value="Iniciar Sesion" class="boton-azul">
            </form> 
        </div>
    </main>
<?php
incluirTemplates('footer');
?>