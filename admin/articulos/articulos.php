<?php

require '../../includes/app.php';
incluirTemplates('header');

use App\Categorias;
use App\Articulos;

$categorias = new Categorias;
$articulos = new Articulos;
$ext = null;
$errores = [];

$articulos = Articulos::all();


$categorias = Categorias::all();
$categoriasPost = new Categorias;

if(isset($_GET['cat'])) {
    $cat = $_GET['cat'];
    $cat = filter_var($cat, FILTER_VALIDATE_INT);
    $articulos = Articulos::where($cat);
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     $articulos = new Articulos;
    //     if ($_POST['articulos']) {
    //         $args = $_POST['articulos'];
    //         $articulos->sincronizar($args);
    //         $dir = "/admin/articulos/articulos.php";
    //         $articulos->guardar($dir);
    //     }
    //     if ($_POST['articulo']) {
    //         $args = $_POST['articulo'];
    //         $articulos->sincronizar($args);
    //         $dir = "/admin/articulos/articulos.php";
    //         $articulos->guardar($dir);
    //     }
    // }
}

$r = $_GET['r'] ?? null;
$r = filter_var($r, FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articulos = new Articulos;
    if(isset($_POST['borrarArt'])){
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        
            if ($id) {
                // compara lo que vamos a eliminar
                $articulos = Articulos::find($id);
                if($articulos) {
                    // Llamo al metodo eliminar
                    $dir = "/admin/articulos/articulos.php";
                    $articulos->eliminar($dir);
                }
            }
            if(!$id) {
                $errorArt = new Articulos();
                $eliminar = $errorArt->validarEliminar();
                $errores = Articulos::getErrores();
                $articulos = [];
            }
    }

    if (isset($_POST['borrarCat'])) {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id) {
            // compara lo que vamos a eliminar
            $categorias = Categorias::find($id);
            
            if($categorias) {

                $eliminar = $categorias->validarEliminar();
                // Llamo al metodo eliminar
                $dir = "/admin/articulos/articulos.php";
                $categorias->eliminar($dir);
            } 
            
        }
        if(!$id) {
            $errorCat = new Categorias();
            $eliminar = $errorCat->validarEliminar();
            $errores = Categorias::getErrores();
            $articulos = [];
        }
    }
    if (isset($_POST['categorias'])) {
        $args = $_POST['categorias'];
        $categoriasPost->sincronizar($args);
        $errores = $categoriasPost->validar($ext);
        if(empty($errores)) {
            $dir = "/admin/articulos/articulos.php";
            $categoriasPost->guardar($dir);
        } else {
            $errores = Categorias::getErrores();
            $articulos = [];
        }
    }
    if (isset($_POST['guardar'])) {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id) {
            $args = $_POST['categoria'];
            $categoriasPost = Categorias::find($id);
            $categoriasPost->sincronizar($args);
            $errores = $categoriasPost->validar($ext);
            if(empty($errores)) {
                $dir = "/admin/articulos/articulos.php";
                $categoriasPost->guardar($dir);
            } else {
                $errores = Categorias::getErrores();
                $articulos = [];
            }
        }
    }
    if (isset($_POST['articulos'])) {
        $args = $_POST['articulos'];
        $articulos->sincronizar($args);
        $codigo = $args['codigo'];
        $errores = $articulos->validar($ext);
        if(empty($errores)) {
            $duplicado = new Articulos;
            $dir = "/admin/articulos/articulos.php";
            $duplicado = $articulos->codigo($codigo, $dir);
            $articulos->guardar($dir);
        } else {
            $errores = Articulos::getErrores();
            $articulos = [];
        }
        

    }
    if (isset($_POST['articulo'])) {
        $args = $_POST['articulo'];
        $articulos->sincronizar($args);
        $errores = $articulos->validarActualizar();
        if(empty($errores)) {
            $dir = "/admin/articulos/articulos.php";
            $articulos->guardar($dir);
        } else {
            $errores = Articulos::getErrores();
            $articulos = [];
        }
    }
    if (isset($_POST['codigo'])){
        $codigo = $_POST['codigo'];
        $articulos = new Articulos;
        $articulos = $articulos->buscar($codigo);
    }
    
}

?>
<main class="contenedor seccion sesion">
    <h1 class="texto-centrado">Gestion de articulos</h1>
    <?php
        $mensaje = mostrarNotificaciones(intval($r));
        if (in_array($r, ['8', '9', '10', '11'])) {
            if($mensaje) { ?>
            <p class="alerta error"><?php echo s($mensaje); ?></p>
            <?php } } else if($mensaje) { ?>
            <p class="alerta insertado"><?php echo s($mensaje); ?></p>
        <?php } ?>
        <?php foreach($errores as $error) { ?>
        <p class="alerta error"><?php echo $error ?></p>
        <?php } ?>
        
        <!-- ventana modal -->
        <div class="abrirCat ventana-modal">
            <div class="modal">
                <h2 class="texto-centrado">Editar categorias</h2>
                <button id=btnCerrar class="boton-rojo">Cerrar</button>
                <div class="contenido-modal">
                    <div class="editor-categorias formulario">
                        <form method="POST" class="int-formulario">
                            <label for="nombre">Nombre Categoria:</label>
                            <input type="text" name="categorias[nombre]" placeholder="Introduce un nombre" id="nombre" maxlength="20" value="<?php echo s($_POST['categorias']['nombre'] ?? ''); ?>" required>
                            <div class="boton-derecha">
                                <input type="submit" value="Crear" class="boton-azul guardar">
                            </div>
                        </form>
                    </div>
                    <div>
                        <table class="categorias">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($categorias as $categoria) { ?>
                                <tr>
                                    <td class="categoria-nombre"><?php echo $categoria->nombre?></a></td>
                                    <td class="opciones">
                                        <form method="POST" id="<?php echo "form" . $categoria->id?>">
                                            <input type="hidden" name="id" value="<?php echo s($categoria->id) ?>" class="boton-rojo guardar">
                                            <input type="hidden" name="borrarCat" value="<?php echo s('EliminarCat') ?>" class="boton-rojo guardar">
                                            <button type="submit" class="boton-rojo"><img class="icono" src="/build/img/iconos/icons8-papelera-llena-100.png" alt="Icono papelera"></button>
                                        </form>
                                        <button id="<?php echo $categoria->id?>" class="boton-verde abrirEditarCat<?php echo $categoria->id?>"><img class="icono" src="/build/img/iconos/icons8-punta-de-lápiz-100.png" alt="Icono editar"></button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!--fin ventana modal -->
        <?php foreach($categorias as $categoria) { ?>
        <div class="editarCat<?php echo $categoria->id?> ventana-modal">
            <div class="modal">
                <h2 class="texto-centrado">Actualizar categoria</h2>
                <button id="<?php echo $categoria->id?>" class="boton-rojo cerrarEditarCat<?php echo $categoria->id?>">Cerrar</button>
                <div class="contenido-modal2">
                    <div class="editor-categorias formulario">
                        <form method="POST" class="int-formulario">
                            <label for="nombre">Nombre Categoria:</label>
                            <input type="text" name="categoria[nombre]" placeholder="Introduce un nombre" id="nombre" maxlength="20" value="<?php echo s($categoria->nombre) ?>" required>
                            <div class="boton-derecha">
                                <input type="hidden" name="id" value="<?php echo s($categoria->id) ?>">
                                <input type="hidden" name="guardar" value="<?php echo s('actualizarCat') ?>">
                                <input type="submit" value="guardar" class="boton-azul guardar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        <?php } ?>
        <!-- ventana modal -->
        <div class="abrirArt ventana-modal">
            <div class="modal">
                <h2 class="texto-centrado">Crear Articulo</h2>
                <button id=btnCerrarArt class="boton-rojo">Cerrar</button>
                <div class="contenido-modal contenido-articulos">
                    <div class="editor-articulos formulario">
                        <form method="POST">
                            <fieldset class="int-formulario">

                                <label for="codigo">Código:</label>
                                <input type="number" name="articulos[codigo]" placeholder="Código" id="codigo" maxlength="9" value="<?php echo s($_POST['articulos']['codigo'] ?? ''); ?>" required>

                                <label for="nombre">Nombre Artículo:</label>
                                <input type="text" name="articulos[nombre]" placeholder="Nombre artículo" id="nombre" maxlength="20" value="<?php echo s($_POST['articulos']['nombre'] ?? ''); ?>" required>

                                <label for="categoriasId">Categoria:</label>
                                <select name="articulos[categoriasId]">
                                    
                                    <?php foreach($categorias as $categoria ) { ?>
                                        <option <?php echo $categoria->id ? 'selected' : ''; ?> value="<?php echo s($categoria->id); ?>" > <?php echo s($categoria->nombre); ?> </option>
                                        <?php } ?>
                                </select>
                                <label for="iva">% IVA:</label>
                                <input type="number" step="0.01" name="articulos[iva]" placeholder="Iva" id="iva" maxlength="8" value="<?php echo s(number_format(21, 2)) ?>" readonly onchange="calcularPrecioVenta(); calcularBase(); calcularBaseCompra(); calcularPrecioCompra();">
                                
                                <label for="pvp">Precio venta:</label>
                                <input type="number" step="0.01" name="articulos[pvp]" placeholder="Precio venta" id="pvp" maxlength="8" value="<?php echo s($_POST['articulos']['pvp'] ?? ''); ?>" required onchange="calcularBase()">

                                <label for="baseArtPvp">Base:</label>
                                <input type="number" step="0.01" name="articulos[base]" placeholder="Base" id="basePvp"  maxlength="8" value="<?php echo s($_POST['articulos']['base'] ?? ''); ?>" required onchange="calcularPrecioVenta()">
                                

                                <label for="precioCompra">Precio Compra:</label>
                                <input type="number" step="0.01" name="articulos[precioCompra]" placeholder="Precio compra" id="precioCompra" maxlength="8" value="<?php echo s($_POST['articulos']['precioCompra'] ?? ''); ?>" required onchange="calcularBaseCompra()">

                                <label for="baseCompra">Base Compra:</label>
                                <input type="number" step="0.01" name="articulos[baseCompra]" placeholder="Base compra" id="baseCompra" maxlength="8" value="<?php echo s($_POST['articulos']['baseCompra'] ?? ''); ?>" required onchange="calcularPrecioCompra()">

                                <label for="stock">Stock:</label>
                                <input type="number" name="articulos[stock]" placeholder="Stock" id="stock" maxlength="8" value="<?php echo s($_POST['articulos']['stock'] ?? ''); ?>" required>

                            </fieldset>
                            <div class="boton-derecha">
                                <input type="submit" value="Crear" class="boton-azul guardar">
                            </div>
                        </form>
                    </div>
                    <div>
                        
                    </div>
                </div>
            </div>
        </div> <!--fin ventana modal -->
    <div class="columnas">
        <div class="div-categorias">
            <div class="juntar-botones">
                <button id="btnNewCat" class="boton-naranja">Editar</button>
            </div>
            <table class="categorias">
                <thead>
                    <tr>
                        <th>Categorias</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categorias as $categoria) { ?>
                    <tr>
                        <td class="categoria-nombre"><a class="categoria-enlace" href="?cat=<?php echo $categoria->id ?>"><?php echo $categoria->nombre?></a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="div-articulos">
            <div>
                <form method="POST" >
                    <label for="coddigo">Código o Nombre:</label>
                    <input type="text" name="codigo" value="<?php s(isset($_POST['codigo'])) ?>" autocomplete="off">
                    <input type="submit" value="Buscar">
                </form>
            </div>
            <div class="separar-botones">
                <a href="/menu.php" class="boton-volver">Volver</a>
                <button id="btnNewArt" class="boton-naranja">Crear</button>
            </div>
            <table class="articulos">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Base</th>
                        <th>Iva</th>
                        <th>pvp</th>
                        <th>stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($articulos as $articulo) { ?>
                    <tr>
                        <td><?php echo $articulo->id?></td>
                        <td><?php echo $articulo->codigo?></td>
                        <td><?php echo $articulo->nombre?></td>
                        <td><?php echo $articulo->base?></td>
                        <td><?php echo $articulo->iva?></td>
                        <td><?php echo $articulo->pvp?></td>
                        <td><?php echo $articulo->stock?></td>
                        <td class="opciones">
                            <form method="POST" id="<?php echo "form" . $articulo->id?>">
                                <input type="hidden" name="id" value="<?php echo s($articulo->id) ?>" class="boton-rojo guardar">
                                <input type="submit" name="borrarArt" value="<?php echo s('Eliminar') ?>" class="boton-rojo guardar">
                            </form>
                            <button id="<?php echo $articulo->id?>" class="boton-verde guardar btnNewArtA<?php echo $articulo->id?>">Actualizar</button>
                        </td>
                    </tr>
                    <?php }  ?>
                </tbody>
            </table>
            <?php foreach($articulos as $articulo) { ?>
            <!-- ventana modal -->
            <div class="abrirArtA<?php echo $articulo->id?> ventana-modal">
                <div class="modal">
                    <h2 class="texto-centrado">Actualizar Articulo</h2>
                    <button id="<?php echo $articulo->id?>" class="boton-rojo btnCerrarArtA<?php echo $articulo->id?>">Cerrar</button>
                    <div class="contenido-modal contenido-articulos">
                        <div class="editor-articulos formulario">
                            <form id="mi-modal" method="POST">
                                <fieldset class="int-formulario">

                                    <input type="hidden" name="articulo[id]" placeholder="Código" id="id" maxlength="9" value="<?php echo s($articulo->id); ?>" required readonly>

                                    <label for="codigo">Código:</label>
                                    <input type="number" name="articulo[codigo]" placeholder="Código" id="codigo" maxlength="9" value="<?php echo s($articulo->codigo); ?>" required>

                                    <label for="nombre">Nombre Artículo:</label>
                                    <input type="text" name="articulo[nombre]" placeholder="Nombre artículo" id="nombre" maxlength="20" value="<?php echo s($articulo->nombre); ?>" required>

                                    <label for="categoriasId">Categoria:</label>
                                    <select name="articulo[categoriasId]">
                                        
                                        <?php foreach($categorias as $categoria ) { ?>
                                            <option <?php echo $articulo->categoriasId === $categoria->id ? 'selected' : ''; ?> value="<?php echo s($categoria->id); ?>" > <?php echo s($categoria->nombre); ?> </option>
                                            <?php } ?>
                                    </select>
                                    <label for="iva">% IVA:</label>
                                    <input type="number" step="0.01" name="articulo[iva]" placeholder="Iva" id="iva<?php echo $articulo->id?>" maxlength="8" value="21.00" readonly >
                                    
                                    <label for="pvp">Precio venta:</label>
                                    <input type="number" step="0.01" name="articulo[pvp]" placeholder="Precio venta" id="pvp<?php echo $articulo->id?>" maxlength="8" value="<?php echo s($articulo->pvp); ?>" required >

                                    <label for="baseArtPvp">Base:</label>
                                    <input type="number" step="0.01" name="articulo[base]" placeholder="Base" id="basePvp<?php echo $articulo->id?>" maxlength="8" value="<?php echo s($articulo->base); ?>" required >
                                    

                                    <label for="precioCompra">Precio Compra:</label>
                                    <input type="number" step="0.01" name="articulo[precioCompra]" placeholder="Precio compra" id="precioCompra<?php echo $articulo->id?>" maxlength="8" value="<?php echo s($articulo->precioCompra); ?>" required >

                                    <label for="baseCompra">Base Compra:</label>
                                    <input type="number" step="0.01" name="articulo[baseCompra]" placeholder="Base compra" id="baseCompra<?php echo $articulo->id?>" maxlength="8" value="<?php echo s($articulo->baseCompra); ?>" required >

                                    <label for="stock">Stock:</label>
                                    <input type="number" name="articulo[stock]" placeholder="Stock" id="stock" maxlength="8" value="<?php echo s($articulo->stock); ?>" required>

                                </fieldset>
                                <div class="boton-derecha">
                                    <input type="submit" value="Actualizar" class="boton-azul guardar">
                                </div>
                            </form>
                        </div>
                        <div>
                            
                        </div>
                    </div>
                </div>
            </div> <!--fin ventana modal -->
            <?php } ?>
        </div>
    </div>
</main>
<?php
incluirTemplates('footer');
?>