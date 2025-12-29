<?php


require_once "Usuario.php";

require_once "Articulos.php";


$database = new Database();

$db = $database->getConnection();

require_once "../src/crearFicheroJson.php";

session_start();

if ($_SESSION['inicioSesion'] == true) {


    $usuario = new Usuario($database);


    $id_usuario =  $_SESSION['id_usuario'];


    // Hacemos una consulta a la base da datos para obtener el nombre 

    $datosUsuario = $usuario->obtenerUsuarioPorId($id_usuario);


    $nombreUsuario = $datosUsuario['nombre'];

    creaYactualiza($usuario);

   




    $_SESSION['usuarioNombre'] = $nombreUsuario;


    $_SESSION['avatar'] =  $datosUsuario['avatar'];


    $todos = $usuario->listarUsuarios();

    header('Content-Type: text/html; charset=utf-8');
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <link rel="stylesheet" href="../css/estilo.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
            integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../css/estilo.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
        <title>TrueChange</title>
    </head>

    <body>






        <header class="header">

            <div class="px-2 py-1 bg-opacity-30 bg-info bg-gradient text-white contenedor">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <img src="imagenes/icono_proyecto.png" alt="icono de la aplicacion" width="100" height="100">
                        <!-- Ejemplo en PHP -->





                        <div class="dropdown"> <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src=<?php print($_SESSION['avatar']) ?> alt="Foto de perfil " width="60" height="60" class="rounded-circle me-2">
                                <strong> <?php print($_SESSION['usuarioNombre']) ?></strong> </a>


                            <ul class="dropdown-menu text-small shadow" style="z-index: 2000;">
                                <li><a class="btn dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#avatarModal">Cambiar foto de perfil</a></li>
                                <li><a class="btn dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#subirModal">Subir producto</a></li>
                                <li><a class="dropdown-item" href="#">Mi perfil</a></li>
                                <li><a class="dropdown-item" href="#">Eliminar articulo</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                            </ul>
                        </div>


                        <!-- Modal de cambio de avatar -->

                        <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Foto de perfil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="../src/cambiarAvatar.php" method="post" enctype="multipart/form-data">

                                            <div class="col-md-12">
                                                <label for="fileToUpload" class="form-label">Selecciona una imagen para subir:</label>
                                                <input type="file" class="form-control" id="fileToUpload" name="fileToUpload">
                                            </div>

                                            <div class="modal-footer">
                                                <input type="submit" class="btn btn-primary" value="Subir Imagen" name="submit">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal subir producto-->

                        <div class="modal" id="subirModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Rellena los datos de tu anuncio</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3" action="../src/subirArticulo.php" method="post" enctype="multipart/form-data">
                                            <div class="col-md-12">
                                                <label for="titulo" class="form-label" name="titulo">Titulo</label>
                                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                                            </div>

                                            <p>Selecciona el estado del artículo:</p>
                                            <div>
                                                <input class="form-check-input" type="radio" id="nuevo" name="estado" value="nuevo">
                                                <label for="nuevo">Nuevo</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="como-nuevo" name="estado" value="como nuevo">
                                                <label for="como-nuevo">Como nuevo</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="usado" name="estado" value="usado" checked>
                                                <label for="usado">Usado</label>
                                            </div>
                                            <div>
                                                <input class="form-check-input" type="radio" id="deteriorado" name="estado" value="deteriorado">
                                                <label for="deteriorado">Deteriorado</label>
                                            </div>

                                            <div class="col-md-12">


                                                <select class="form-select " name="categoria">
                                                    <option selected disabled>Categoria</option>
                                                    <option value="coches">Coches</option>
                                                    <option value="motos">Motos</option>
                                                    <option value="motor y accesorios">Motor y accesorios</option>
                                                    <option value="moda y accesorios">Moda y accesorios</option>
                                                    <option value="inmobiliaria">Inmobiliaria</option>
                                                    <option value="tecnologia y electronica">Tecnología y electrónica</option>
                                                    <option value="deporte y ocio">Deporte y ocio</option>
                                                    <option value="bicicletas">Bicicletas</option>
                                                    <option value="hogar y jardin">Hogar y jardin</option>
                                                    <option value="electrodomesticos">Electrodomésticos</option>
                                                    <option value="cine libros y musica">Cine libros y música</option>
                                                    <option value="niños y bebes">Niños y bebés</option>
                                                    <option value="coleccionismo">Coleccionismo</option>
                                                    <option value="construccion y reformas">Construccion y reformas</option>
                                                    <option value="industria  agricultura">Industria y agricultura</option>
                                                    <option value="empleo">Empleo</option>
                                                    <option value="servicios">Servicios</option>
                                                    <option value="otros">Otros...</option>

                                                </select>



                                            </div>
                                            <div class="col-md-12">

                                                <textarea id="descripcion" class="form-control" name="descripcion" placeholder="Descripción"></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="cambio" class="form-label">Quiero cambiar por...</label>
                                                <input type="text" class="form-control" id="cambio" name="cambio" required>
                                            </div>

                                            <label for="foto">Selecciona una imagen:</label><br>
                                            <!-- El campo de entrada tipo 'file' -->
                                            <input type="file" name="foto" id="foto" required>


                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Borrar</button>
                                                <button type="submit" class="btn btn-primary" name="publicar">Publicar</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div>

        </header>

        <!-- Barra de navegación-->
     <div class="barraNavegacion mx-auto sticky-top bg-white">

            <nav class="navbar navbar-expand-lg ">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
                        aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-item nav-underline">
                            <li class="nav-item">
                                <select class="form-select " name="categoria" id="campoCategoria" onclick="filtrarPorCategoria(event)">
                                    <option selected disabled>Categoria</option>
                                    <option value="coches">Coches</option>
                                    <option value="motos">Motos</option>
                                    <option value="motor y accesorios">Motor y accesorios</option>
                                    <option value="moda y accesorios">Moda y accesorios</option>
                                    <option value="inmobiliaria">Inmobiliaria</option>
                                    <option value="tecnologia y electronica">Tecnología y electrónica</option>
                                    <option value="deporte y ocio">Deporte y ocio</option>
                                    <option value="bicicletas">Bicicletas</option>
                                    <option value="hogar y jardin">Hogar y jardin</option>
                                    <option value="electrodomesticos">Electrodomésticos</option>
                                    <option value="cine libros y musica">Cine libros y música</option>
                                    <option value="niños y bebes">Niños y bebés</option>
                                    <option value="coleccionismo">Coleccionismo</option>
                                    <option value="construccion y reformas">Construccion y reformas</option>
                                    <option value="industria  agricultura">Industria y agricultura</option>
                                    <option value="empleo">Empleo</option>
                                    <option value="servicios">Servicios</option>
                                    <option value="otros">Otros...</option>

                                </select>
                            </li>


                            <li class="nav-item ">
                                <a class="nav-link" href="#">Electronica</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Informática</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Hogar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Coches</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Motos</a>
                            </li>
                        </ul>
                    </div>
                    
                    <form class="d-flex col-lg-5 col-md-8 col-sm-9" role="search">
                        <input id="campoFiltro" class="form-control me-2 text-primary" type="search" placeholder="Ej.Iphone" aria-label="Search">
                        <button onclick="aplicarFiltro(event)" class="btn btn-outline-primary" type="submit">Buscar</button>

                    </form>
                </div>
 <a href="Chat/listado_chats.php" class="nav-link">
    Mensajes 
    <span id="notif-badge" class="badge bg-danger" style="display:none;">0</span>
</a>
            </nav>

        </div>
        </div>

        <!-- Hasta aquí la barra de navegación-->

        <div class="d-flex bg-body-tertiary ">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary col-lg-6 col-sm-12">
<div class="container mb-4">
    <div class="row g-3 align-items-center bg-light p-3 rounded shadow-sm">
        
        <div class="col-md-4">
            <label class="form-label fw-bold small">¿Qué buscas?</label>
            <input type="text" id="buscador-general" class="form-control" placeholder="Ej: Bicicleta, Móvil...">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold small">¿Qué ofreces a cambio?</label>
            <input type="text" id="buscador-cambio" class="form-control" placeholder="Ej: Busco gente que quiera una Guitarra...">
            <small class="text-muted" style="font-size: 0.8em;">Filtra por lo que el vendedor pide.</small>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold small">Ordenar por:</label>
            <select id="filtro-orden" class="form-select">
                <option value="reciente" selected>Más recientes (Nuevos primero)</option>
                <option value="antiguo">Más antiguos</option>
                <option value="nombre_asc">Nombre: A - Z</option>
                <option value="nombre_desc">Nombre: Z - A</option>
            </select>
        </div>
    </div>
</div>

                <hr>
            </div>


            <div id="carouselExampleInterval" class="carousel slide text-center flex-shrink-0 p-3 bg-body-tertiary col-lg-6 col-sm-12" data-bs-ride="carousel">
                <div class="carousel-inner text-center">
                    <div class="carousel-item active" data-bs-interval="10000">
                        <img src="imagenes/manos.jpg" class="d-block w-100 img-fluid " alt="Foto de ROMAN ODINTSOV: https://www.pexels.com/es-es/foto/manos-sujetando-cartulina-carton-12725405/">
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <img src="imagenes/electronica.jpg" class="d-block w-100 img-fluid" alt="Foto de ATC Comm Photo: https://www.pexels.com/es-es/foto/primer-plano-de-la-camara-sobre-fondo-negro-306763/">
                    </div>
                    <div class="carousel-item">
                        <img src="imagenes/moto.jpg" class="d-block w-100 img-fluid" alt="Foto de Pragyan Bezbaruah: https://www.pexels.com/es-es/foto/motocicleta-en-medio-de-la-carretera-1715193/">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>


        <!--  Añadimos las cards con los articulos guardados-->

        <div class="container my-5 contenedor1" id="articulos">
            <h1 id="titulo1">Artículos</h1>
            <div class="row" id="resultados">
            </div>
        </div>

        <div class="container my-5">
    <h2>Mis Productos en Venta</h2>
    <div id="contenedor-mis-productos" class="row">
        </div>


       

        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-primary text-black">
                    <strong class="me-auto">Nuevo Mensaje</strong>
                    <small>Ahora mismo</small>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ¡Tienes un nuevo mensaje sobre un artículo!
                    <br>
                    <a href="chat.php" class="btn btn-sm btn-outline-primary mt-2">Ir al chat</a>
                </div>
            </div>
        </div>

        <script src="../src/script.js"></script>

        <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarProducto">
            <input type="hidden" id="edit_id"> <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" class="form-control" id="edit_titulo" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" id="edit_descripcion" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoría</label>
                <select class="form-select" id="edit_categoria">
                    <option value="tecnologia y electronica">Tecnología y Electrónica</option>
                    <option value="moda y accesorios">Moda y Accesorios</option>
                    <option value="hogar y jardin">Hogar y Jardín</option>
                    <option value="deportes y ocio">Deportes y Ocio</option>
                    </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select class="form-select" id="edit_estado">
                    <option value="nuevo">Nuevo</option>
                    <option value="usado">Usado</option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="guardarCambios()">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>
</div>
<footer class="pie">




</footer>
</body>


    </html>

<?php
} else {

    echo "<script>alert('Registrese para acceder.');</script>";

    header("Location: index.php");
}
?>