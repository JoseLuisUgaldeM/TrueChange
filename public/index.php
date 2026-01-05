<?php



require_once "Usuario.php";

require_once "Articulos.php";

require_once "../src/crearFicheroJson.php";

session_start();


$database = new Database();

$db = $database->getConnection();

$usuario = new Usuario($database);

creaYactualiza($usuario);


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

    <div class="loader">
        <div class="spinner"></div>
        <p>Cargando...</p>
    </div>

    <?php


    ?>
    <header class="header">

        <div class="px-2 py-1 bg-opacity-30 bg-info bg-gradient text-white contenedor">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-around">
                    <img src="imagenes/icono_proyecto.png" alt="icono de la aplicacion" width="100" height="100">


                    <div class="botones">
                        <button id="botonIniciarSesion" type="button" class="btn btn-light text-dark me-2" data-bs-toggle="modal"
                            data-bs-target="#Modal">Inicio sesión</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Registrarme</button>
                    </div>


                    <!-- Modal registro-->


                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Completa los campos del formulario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="row g-3" action="registro.php" method="post">
                                        <div class="col-md-12">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" pattern="[a-zA-Z\s]+" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="primerApellido" class="form-label">Primer pellido</label>
                                            <input type="text" class="form-control" id="primerApellido" name="primerApellido" pattern="[a-zA-Z\s]+" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="segundoApellido" name="segundoApellido" pattern="[a-zA-Z\s]+" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="usuarioNombre" class="form-label">Nombre de usuario</label>
                                            <input type="text" class="form-control" id="usuarioNombre" name="usuarioNombre" pattern="[a-zA-Z\s]+">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="pass" class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" id="pass" name="pass" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="email" class="form-label">Correo electrónico</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="inputGroupPrepend2">@</span>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    pattern="[a-zA-Z0-9]+@[a-zA-Z0-9]+.[a-zA-Z]+" aria-describedby="inputGroupPrepend2"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ciudad" class="form-label">Ciudad</label>
                                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                                        </div>
                                        <div class="col-md-6">

                                        </div>


                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                                <label class="form-check-label" for="invalidCheck2">
                                                    Acepta los términos y condiciones
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" name="enviar" type="submit">Enviar formulario</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Modal inicio de sesión-->

                   
                </div>
            </div>
        </div>


    </header>


    <!-- Barra de navegación-->


    <div class="barraNavegacion mx-auto ">

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
                                <option selected disabled>Categoría</option>
                                <option value="Coches">Coches</option>
                                <option value="Motos">Motos</option>
                                <option value="Motor y accesorios">Motor y accesorios</option>
                                <option value="Moda y accesorios">Moda y accesorios</option>
                                <option value="inmobiliaria">Inmobiliaria</option>
                                <option value="Tecnología y electrónica">Tecnología y electrónica</option>
                                <option value="Deporte y ocio">Deporte y ocio</option>
                                <option value="Bicicletas">Bicicletas</option>
                                <option value="Hogar y jardín">Hogar y jardin</option>
                                <option value="Electrodomésticos">Electrodomésticos</option>
                                <option value="Cine libros y música">Cine libros y música</option>
                                <option value="Niños y bebés">Niños y bebés</option>
                                <option value="Coleccionismo">Coleccionismo</option>
                                <option value="Construcción y reformas">Construccion y reformas</option>
                                <option value="Industria  agricultura">Industria y agricultura</option>
                                <option value="Empleo">Empleo</option>
                                <option value="Servicios">Servicios</option>
                                <option value="Otros">Otros...</option>

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
        </nav>

    </div>
    </div>
    <!-- Hasta aquí la barra de navegación-->


    <!-- Mostramos el carrusel de fotos-->


    <div class="container">

        <div class="d-flex bg-body-tertiary container d-flex row text-center my-5">
            <div id="carouselExampleInterval" class="carousel slide d-flex flex-shrink-0 p-3 bg-body-tertiary col-lg-6 col-sm-12" data-bs-ride="carousel">
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
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary col-lg-6 col-sm-12">

                <span class="fs-4 text-center">TRUECHANGE</span>
                <hr>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod vel veritatis a, ducimus provident officiis commodi odio at quia officia possimus assumenda architecto itaque unde dolor soluta tempora praesentium fuga!</p>

                <hr>
            </div>
        </div>
    </div>
    <!-- Mostramos una introduccion acerca de la aplicacion -->


    <!-- Mostramos los articulos-->

    <div class="container my-5">
        <h1>Artículos</h1>
        <div class="row" id="resultados">
        </div>
    </div>

   

        <footer class="pie bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">TrueChange</h5>
                <p>La plataforma líder para el intercambio de artículos. Fomentando la economía circular y el consumo responsable.</p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Explorar</h5>
                <p><a href="#" class="text-white text-decoration-none" onclick="mostrarTodos()">Todos los artículos</a></p>
                <p><a href="#" class="text-white text-decoration-none" onclick="mostrarFavoritos()">Mis Favoritos</a></p>
                <p><a href="#" class="text-white text-decoration-none" onclick="mostrarMisProductos()">Mis Publicaciones</a></p>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Ayuda</h5>
                <p><a href="#" class="text-white text-decoration-none">Cómo funciona</a></p>
                <p><a href="#" class="text-white text-decoration-none">Reglas de intercambio</a></p>
                <p><a href="#" class="text-white text-decoration-none">Contacto</a></p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Síguenos</h5>
                <div class="d-flex justify-content-center justify-content-md-start">
                    <a href="#" class="text-white me-4"><i class="fa fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white me-4"><i class="fa fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white me-4"><i class="fa fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fa fa-linkedin fa-lg"></i></a>
                </div>
            </div>
        </div>

        <hr class="mb-4 mt-5">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p>© 2025 Copyright: <strong class="text-primary">TrueChange.com</strong> - Dale una segunda vida a tus cosas.</p>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="text-center text-md-end">
                   <small class="text-muted">Desarrollado con <i class="fa fa-heart text-danger"></i> para un mundo sostenible.</small>
                </div>
            </div>
        </div>
    </div>

    
</footer>

    <script>
        window.onload = function() {
            const loader = document.querySelector('.loader');
            loader.style.transition = 'opacity 0.5s ease'; // Transición para el desvanecimiento
            loader.style.opacity = '0'; // Hace que el loader se desvanezca

            // Opcional: Eliminar el loader del DOM después de la transición
            setTimeout(() => {
                loader.remove();
            }, 500);
        };
    </script>
    <script src="../src/script.js"></script>

 <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">¡¡Bienvenido/a a TrueChange!!</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="login.php" class="row g-3">
                                        <div class="col-md-12">
                                            <label for="usuario" class="form-label">Usuario</label>
                                            <input type="text" name="usuario" class="form-control" id="usuario" pattern="^[a-zA-Z\s]+$" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="contrasena" class="form-label">Contraseña</label>
                                            <input type="password" name="pass" class="form-control" id="contrasena" required>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit" name="iniciarSesion">Entrar</button>
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
</body>

</html>