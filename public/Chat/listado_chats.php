<?php
require 'db_config.php'; // Para usar la conexión $pdo y las constantes
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Mensajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .chat-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .chat-item:hover {
        background-color: #f8f9fa;
        border-left: 4px solid #0d6efd;
    }
    .unread {
        background-color: #f0f7ff;
        border-left: 4px solid #dc3545;
    }
    .envelope-icon {
        font-size: 1.5rem;
        margin-right: 15px;
        color: #6c757d;
    }
    .unread .envelope-icon {
        color: #dc3545; /* Rojo si no está leído */
    }
</style>
</head>
<body>
    <div class="container mt-5">
   
    <h2>Bandeja de Entrada</h2>
    <div id="lista-conversaciones" class="list-group">
        </div>
</div>
<div class="container mt-5">
        <a href="../../src/php/sesionIniciada.php" class="btn btn-sm btn-outline-secondary me-3">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
        <div>
<script>
// Función para cargar la lista de personas con las que hay chat
function cargarBandeja() {
    fetch('get_chat_list.php')
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById('lista-conversaciones');
            if (!contenedor) return;
            contenedor.innerHTML = "";

            if (data.chats && Array.isArray(data.chats)) {
                data.chats.forEach(chat => {
                    const totalNoLeidos = parseInt(chat.total_no_leidos) || 0;
                    
                    // --- AQUÍ ESTÁ EL CAMBIO DEL SOBRE ---
                    // Si hay mensajes nuevos: sobre CERRADO (fa-envelope) y color ROJO (text-danger)
                    // Si no hay nuevos: sobre ABIERTO (fa-envelope-open) y color GRIS (text-muted)
                    const nombreAMostrar = chat.nombre_usuario ? chat.nombre_usuario : "Usuario Desconocido";
                    const iconoSobre = totalNoLeidos > 0 ? 'fa-envelope text-danger' : 'fa-envelope-open text-muted';
                    const fondoNoLeido = totalNoLeidos > 0 ? 'bg-light' : '';
                    const negrita = totalNoLeidos > 0 ? 'fw-bold' : '';

                  contenedor.innerHTML += `
                                            <div onclick="abrirChat(${chat.partner_id})" 
                                                class="list-group-item list-group-item-action d-flex align-items-center" 
                                                style="cursor:pointer">
                                                
                                                <div class="me-3">
                                                    <i class="fa-solid ${iconoSobre} fa-2x"></i>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="mb-1 ${totalNoLeidos > 0 ? 'fw-bold' : ''}">${nombreAMostrar}</h6>
                                                        <small class="text-muted">${chat.fecha}</small>
                                                    </div>
                                                    <p class="mb-0 text-truncate" style="max-width: 80%; font-size: 0.9rem;">
                                                        ${chat.ultimo_mensaje}
                                                    </p>
                                                </div>

                                                ${totalNoLeidos > 0 ? `<span class="badge bg-danger rounded-pill">${totalNoLeidos}</span>` : ''}
                                            </div>`;
                });
            }
        });
}

// Esta función es la que se ejecuta al hacer clic en el sobre/conversación
function abrirChat(id) {
    console.log("Intentando abrir chat con ID:", id);
    
    const formData = new FormData();
    formData.append('id_vendedor', id);

    // 1. Enviamos el ID a db_config.php para que lo guarde en $_SESSION['vendedor_chat_id']
    fetch('db_config.php', { 
        method: 'POST', 
        body: formData 
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en el servidor');
        return response.text();
    })
    .then(data => {
        console.log("Sesión guardada correctamente:", data);
        // 2. SOLO CUANDO EL SERVIDOR RESPONDE, cambiamos de página
        window.location.href = 'chat.php';
    })
    .catch(err => {
        console.error("Error al preparar el chat:", err);
        // Si falla el fetch, intentamos ir directamente (como plan B)
        window.location.href = 'chat.php?partner_id=' + id;
    });
}
function guardarPartner(id) {
    // Al hacer clic, enviamos el ID al servidor para que db_config.php 
    // lo guarde en la sesión antes de ir a chat.php
    const formData = new FormData();
    formData.append('id_vendedor', id);
    fetch('db_config.php', { method: 'POST', body: formData });
}

document.addEventListener('DOMContentLoaded', cargarBandeja);
</script>
</body>
</html>