// Variable para almacenar todos los datos una vez que se cargan
let todosLosDatos = [];
const contenedorResultados = document.getElementById('resultados');
const contenedorResultadosFiltrados = document.getElementById('resultadosFiltrados');
var contador = 0;
// Variable para evitar notificaciones repetidas
// Consultar cada 10 segundos
setInterval(verificarNuevosMensajes , 5000);
let mensajesDetectados = 0;

function verificarNuevosMensajes() {
    fetch('../public/Chat/check_notifications.php')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('notif-badge');
            // CAMBIO AQUÍ: de data.nuevos a data.count
            if (data.count > 0) { 
                if(badge) {
                    badge.innerText = data.count;
                    badge.style.display = 'block';
                }
                document.title = `(${data.count}) Mensajes nuevos`;
            } else if(badge) {
                badge.style.display = 'none';
                document.title = "TrueChange";
            }
        });
}



/**
 * Función para cargar los datos del JSON al inicio.
 */
async function inicializarDatos() {

    try {
        contenedorResultados.innerHTML = `<p>Cargando datos...</p>`;
        const respuesta = await fetch('datos_usuario.json');
        
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }
        
        todosLosDatos = await respuesta.json();
        
        // Muestra todos los datos al inicio
        mostrarDatos(todosLosDatos, contenedorResultados);
        aplicarFiltros();
    } catch (error) {
        console.error("Hubo un error al cargar o procesar los datos:", error);
        contenedorResultados.innerHTML = `<p style="color: red;">Error: No se pudieron cargar los datos. ${error.message}</p>`;
    }
}




function filtrarPorCategoria(event) {
    event.preventDefault();
    const campoFiltro = document.getElementById('campoCategoria').value;
    const valorFiltro = 'categoria';



    // El filtro de texto debe ser insensible a mayúsculas/minúsculas
    const valorLowerCase = campoFiltro.toLowerCase();
    
    // 1. Aplicar el filtro a los datos cargados previamente
    const datosFiltrados = todosLosDatos.filter(item => {
        const itemValue = item[valorFiltro];

        if (typeof itemValue === 'string') {
            // Filtrado de texto (insensible a mayúsculas/minúsculas y busca subcadenas)
            return itemValue.toLowerCase().includes(valorLowerCase);
        } else if (typeof itemValue === 'number' && !isNaN(parseFloat(valorLowerCase))) {
            // Filtrado de números (ej. para precios)
            // Esto busca coincidencias exactas con el número introducido.
            return itemValue === parseFloat(valorLowerCase);
        }


        // Si no es un string ni un número (o si el campo no existe), no lo incluimos
        return false;
    });

    // 2. Mostrar los resultados
    mostrarDatos(datosFiltrados, contenedorResultadosFiltrados, campoFiltro, valorFiltro);
}

/**
 * Función que se ejecuta al hacer clic en "Aplicar Filtro".
*/
function aplicarFiltro(event) {
    event.preventDefault();
    const campoFiltro = document.getElementById('campoFiltro').value;
    const valorFiltro = 'titulo';
    
    
    
    // El filtro de texto debe ser insensible a mayúsculas/minúsculas
    const valorLowerCase = campoFiltro.toLowerCase();
    
    // 1. Aplicar el filtro a los datos cargados previamente
    const datosFiltrados = todosLosDatos.filter(item => {
        const itemValue = item[valorFiltro];
        
        if (typeof itemValue === 'string') {
            // Filtrado de texto (insensible a mayúsculas/minúsculas y busca subcadenas)
            return itemValue.toLowerCase().includes(valorLowerCase);
        } else if (typeof itemValue === 'number' && !isNaN(parseFloat(valorLowerCase))) {
            // Filtrado de números (ej. para precios)
            // Esto busca coincidencias exactas con el número introducido.
            return itemValue === parseFloat(valorLowerCase);
        }
        
        
        // Si no es un string ni un número (o si el campo no existe), no lo incluimos
        return false;
    });
    
    // 2. Mostrar los resultados
    mostrarDatos(datosFiltrados, contenedorResultadosFiltrados, campoFiltro, valorFiltro);
}

/**
 * Función para mostrar todos los datos sin filtro.
*/
function mostrarTodos() {
    mostrarDatos(todosLosDatos, contenedorResultados, contador);
}


/**
 * Función auxiliar para generar el HTML y mostrar los datos.
*/
/**
 * Función auxiliar para generar el HTML y mostrar los datos.
 * CORREGIDA: Separa el Modal de la Card para evitar parpadeos con el efecto hover.
 */
function mostrarDatos(datos, contenedor, campo = null, valor = null) {
    console.log(datos);

    // 1. Limpiar el contenedor de tarjetas
    contenedor.innerHTML = "";
    
    // 2. Buscar o crear el contenedor EXCLUSIVO para los modales
    // Esto asegura que los modales vivan fuera de las cards y no se vean afectados por el 'transform'
    let contenedorModales = document.getElementById('contenedor-modales-dinamicos');
    if (!contenedorModales) {
        contenedorModales = document.createElement('div');
        contenedorModales.id = 'contenedor-modales-dinamicos';
        document.body.appendChild(contenedorModales);
    } else {
        // Si ya existe, lo limpiamos para no acumular modales viejos de búsquedas anteriores
        contenedorModales.innerHTML = "";
    }

    if (datos.length === 0) {
        console.error('No hay articulos que mostrar');
        contenedor.innerHTML = `<div class="alert alert-warning col-12" role="alert">No se encontraron artículos.</div>`;
        return;
    }

    datos.forEach(item => {
        // --- Lógica de fechas ---
        const fechaInicio = new Date(item.fecha_publicacion);
        const fechaActual = new Date();
        fechaInicio.setHours(0, 0, 0, 0);
        fechaActual.setHours(0, 0, 0, 0);
        const diferenciaMilisegundos = fechaActual.getTime() - fechaInicio.getTime();
        const milisegundosEnUnDia = 1000 * 60 * 60 * 24;
        let imprimir = "Publicado hoy";
        let intervalo = Math.floor(diferenciaMilisegundos / milisegundosEnUnDia);

        if (intervalo > 1) imprimir = `Publicado hace ${intervalo} dias `;
        if (intervalo == 1) imprimir = `Publicado hace ${intervalo} dia `;
        
        const imagen = item.ruta_foto ? item.ruta_foto : '../public/imagenes/default.png';

        // --- PARTE A: SOLO LA TARJETA ---
        // Nota: Mantenemos 'card-efecto' aquí para la animación
        const cardHtml = `
        <div class="col-xl-2 col-lg-3 col-sm-12 col mb-4">
            <div class="card h-100 shadow-sm card-efecto">
                <img src="${imagen}" class="card-img-top" alt="${item.titulo}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${item.titulo}</h5>
                    <p class="card-text text-truncate">${item.descripcion}</p>
                    <p class="text-primary fw-bold mt-auto">${item.categoria}</p>
                    
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalArticulo${contador}">
                        Ver articulo
                    </button>
                </div>
            </div>
        </div>`;

        // --- PARTE B: EL MODAL (SEPARADO) ---
        // Este HTML se inyectará al final del body, lejos de la tarjeta animada
        const modalHtml = `
        <div class="modal fade" id="exampleModalArticulo${contador}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1055;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">${item.titulo}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <img src="${imagen}" class="card-img-top mb-3" alt="${item.titulo}">
                        <div class="border p-3 m-2">
                            <h6>Descripción del articulo</h6>
                            <p class="card-text">${item.descripcion}</p>
                        </div>
                        <div class="border p-3 m-2">
                            <h6>Categoría</h6>
                            <p class="text-primary fw-bold mt-auto">${item.categoria}</p>
                        </div>
                        <div class="border p-3 m-2">
                            <h6>Vendedor</h6>
                            <p class="text-primary fw-bold mt-auto">${item.nombre}</p>
                        </div> 
                        <div class="border p-3 m-2">
                            <h6>Fecha publicación</h6>
                            <p class="text-primary fw-bold mt-auto">${imprimir}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary btn-chat btn-enviar-id" data-id="${item.usuario_id}">Enviar mensaje</button>
                    </div>
                </div>
            </div>
        </div>`;
                                            
        // Insertamos cada parte en su contenedor correspondiente
        contenedor.innerHTML += cardHtml;           // La tarjeta va al grid
        contenedorModales.innerHTML += modalHtml;   // El modal va al fondo del body
        
        contador++;
    });
}

// Iniciar la carga de datos al cargar la página
inicializarDatos();

function mostrarNotificacion() {
    reproducirSonido();
    const toastElement = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}

function reproducirSonido() {
    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
    audio.play();
}


// 1. Detectar el clic en el botón de la card
document.addEventListener('click', function(e) {
if (e.target.classList.contains('btn-enviar-id')) {
    // 1. Obtener el ID del vendedor
    const vendedorId = e.target.getAttribute('data-id');
    
    // 2. Enviar el ID a PHP
    fetch('../public/Chat/db_config.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_vendedor=' + encodeURIComponent(vendedorId)
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        return response.text();
    })
    .then(data => {
        console.log('Sesión actualizada:', data);
        // 3. AHORA SÍ, redirigimos después de confirmar el éxito
        window.location.href = '../public/Chat/chat.php';
    })
    .catch(error => {
        console.error('Error al guardar sesión:', error);
        alert('No se pudo iniciar el chat. Intentalo de nuevo.');
    });
}

    fetch('check_notifications.php')
    .then(res => res.json())
    .then(data => {
        if(data.count > 0) {
            // Mostrar el Toast que ya tienes en tu HTML o un punto rojo en el icono de mensajes
            const toast = new bootstrap.Toast(document.getElementById('liveToast'));
            toast.show();
        }
    });
});
// --- PEGAR AL FINAL DE script.js ---

document.addEventListener('DOMContentLoaded', () => {
    cargarMisProductos();
});

// 1. MODIFICAMOS LA FUNCIÓN DE CARGAR PARA AÑADIR BOTONES REALES
async function cargarMisProductos() {
    const contenedor = document.getElementById('contenedor-mis-productos');
    if (!contenedor) return; 

    try {
        const respuesta = await fetch('../public/api_mis_productos.php');
        const misProductos = await respuesta.json();
        
        contenedor.innerHTML = ''; 
        
        if (misProductos.length === 0) {
            contenedor.innerHTML = '<p class="text-muted">Aún no has subido ningún artículo.</p>';
            return;
        }

        misProductos.forEach(producto => {
            const imagen = producto.ruta_foto ? producto.ruta_foto : '../public/imagenes/default.png';
            
            // Preparamos los datos para el botón de editar (evitando errores de comillas)
            const datosProducto = JSON.stringify(producto).replace(/"/g, '&quot;');

            const cardHTML = `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 card-efecto">
                        <div style="position: relative;">
                            <img src="${imagen}" class="card-img-top" alt="${producto.titulo}" style="height: 200px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute top-0 end-0 m-2">${producto.categoria}</span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="${producto.titulo}">${producto.titulo}</h5>
                            <p class="card-text text-truncate text-muted small">${producto.descripcion}</p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                <span class="badge bg-light text-dark border">${producto.estado}</span>
                                
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-1" 
                                            title="Editar"
                                            onclick='abrirModalEditar(${datosProducto})'>
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>

                                    <button class="btn btn-outline-danger btn-sm" 
                                            title="Eliminar"
                                            onclick="eliminarProducto(${producto.articulo_id})">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            contenedor.innerHTML += cardHTML;
        });

    } catch (error) {
        console.error('Error cargando mis productos:', error);
    }
}
// 3. FUNCIÓN PARA ABRIR EL MODAL Y RELLENAR DATOS
var modalBootstrap; // Variable global para controlar el modal

function abrirModalEditar(producto) {
    // Rellenamos los inputs con los datos actuales
    document.getElementById('edit_id').value = producto.articulo_id;
    document.getElementById('edit_titulo').value = producto.titulo;
    document.getElementById('edit_descripcion').value = producto.descripcion;
    document.getElementById('edit_categoria').value = producto.categoria;
    document.getElementById('edit_estado').value = producto.estado;

    // Abrimos el modal usando Bootstrap 5
    var modalElement = document.getElementById('modalEditar');
    modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

// 4. FUNCIÓN PARA GUARDAR LOS CAMBIOS
function guardarCambios() {
    const id = document.getElementById('edit_id').value;
    const titulo = document.getElementById('edit_titulo').value;
    const descripcion = document.getElementById('edit_descripcion').value;
    const categoria = document.getElementById('edit_categoria').value;
    const estado = document.getElementById('edit_estado').value;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('titulo', titulo);
    formData.append('descripcion', descripcion);
    formData.append('categoria', categoria);
    formData.append('estado', estado);

    fetch('../public/editar_articulo.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert("Artículo actualizado correctamente.");
            modalBootstrap.hide(); // Cerramos modal
            cargarMisProductos(); // Recargamos lista
        } else {
            alert("Error al actualizar: " + (data.message || 'Error desconocido'));
        }
    });
}

function eliminarProducto(id) {
    if(!confirm("¿Estás seguro de que quieres eliminar este artículo?")) return;

    const formData = new FormData();
    formData.append('id', id);

    // CAMBIO IMPORTANTE: Intenta usar la ruta sin '../public/'
    // Si tu archivo JS está cargado en sesionIniciada.php, la ruta relativa es directa:
    fetch('eliminar_articulo.php', { 
        method: 'POST',
        body: formData
    })
    .then(res => {
        // Esto nos ayuda a ver si el archivo existe o da error 404/500
        if (!res.ok) {
            throw new Error("Error en la red: " + res.status + " " + res.statusText);
        }
        return res.json(); // Intentamos leer el JSON
    })
    .then(data => {
        if(data.success) {
            alert("Artículo eliminado correctamente.");
            cargarMisProductos(); // Recargar la lista
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Ocurrió un error al intentar borrar. Abre la consola (F12) para ver más detalles.");
    });
}
// --- LÓGICA DE FILTRADO UNIFICADO ---

// 1. Seleccionamos los elementos del DOM
const inputGeneral = document.getElementById('buscador-general');
const inputCambio = document.getElementById('buscador-cambio');
const selectOrden = document.getElementById('filtro-orden');

// 2. Función Maestra: Aplica todos los filtros a la vez
function aplicarFiltros() {
    // Empezamos con una copia de TODOS los datos
    let resultados = [...todosLosDatos];

    // A) FILTRO POR TÍTULO O DESCRIPCIÓN (Buscador General)
    if (inputGeneral && inputGeneral.value.trim() !== "") {
        const texto = inputGeneral.value.toLowerCase();
        resultados = resultados.filter(item => 
            item.titulo.toLowerCase().includes(texto) || 
            item.descripcion.toLowerCase().includes(texto)
        );
    }

    // B) FILTRO POR 'CAMBIO' (Lo que el vendedor busca)
    // Asumimos que la info de qué quiere cambio está en la descripción o un campo específico
    if (inputCambio && inputCambio.value.trim() !== "") {
        const textoCambio = inputCambio.value.toLowerCase();
        resultados = resultados.filter(item => {
            // Buscamos palabras clave como "cambio por", "busco", o si tienes un campo 'preferencia_cambio'
            // Si tienes un campo especifico en tu JSON úsalo aquí: item.preferencia_cambio
            const contenido = (item.descripcion + " " + (item.preferencia_cambio || "")).toLowerCase();
            return contenido.includes(textoCambio);
        });
    }

    // C) ORDENACIÓN
    if (selectOrden) {
        const criterio = selectOrden.value;
        
        if (criterio === 'reciente') {
            resultados.sort((a, b) => new Date(b.fecha_publicacion) - new Date(a.fecha_publicacion));
        } else if (criterio === 'antiguo') {
            resultados.sort((a, b) => new Date(a.fecha_publicacion) - new Date(b.fecha_publicacion));
        } else if (criterio === 'nombre_asc') {
            resultados.sort((a, b) => a.titulo.localeCompare(b.titulo));
        } else if (criterio === 'nombre_desc') {
            resultados.sort((a, b) => b.titulo.localeCompare(a.titulo));
        }
    }

    // 3. Mostrar los resultados finales
    const contenedor = document.getElementById('resultados');
    mostrarDatos(resultados, contenedor);
}

// 3. Asignar los eventos (Escuchamos cuando el usuario escribe o cambia algo)
if (inputGeneral) inputGeneral.addEventListener('input', aplicarFiltros);
if (inputCambio) inputCambio.addEventListener('input', aplicarFiltros);
if (selectOrden) selectOrden.addEventListener('change', aplicarFiltros);

// Opcional: Ejecutar filtro inicial al cargar (para que salga ordenado por reciente)
// setTimeout(() => { aplicarFiltros(); }, 500); // Pequeño retardo para asegurar que los datos cargaron