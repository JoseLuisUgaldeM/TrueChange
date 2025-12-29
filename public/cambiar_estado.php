<?php
// public/cambiar_estado.php

// 1. Incluye tu configuración de base de datos
require 'Chat/db_config.php'; 
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificamos que el usuario esté logueado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "No estás logueado"]);
        exit;
    }

    $idArticulo = $_POST['id'];
    $nuevoEstado = $_POST['estadoArticulo']; // <--- Aquí usamos el nombre nuevo
    $usuarioLogueado = $_SESSION['user_id'];

    // 2. Verificación de seguridad: ¿Es el usuario dueño del artículo?
    // Ajusta el nombre de la tabla 'articulos' si es necesario
    $check = $conn->prepare("SELECT usuario_id FROM articulos WHERE id = ?");
    $check->bind_param("i", $idArticulo);
    $check->execute();
    $result = $check->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if ($row['usuario_id'] == $usuarioLogueado) {
            
            // 3. Actualizamos el campo 'estadoArticulo'
            $sql = "UPDATE articulos SET estadoArticulo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $nuevoEstado, $idArticulo);
            
            if ($stmt->execute()) {
                // Opcional: Si tienes un archivo JSON cache, aquí deberías regenerarlo
                // require_once "../src/crearFicheroJson.php"; 
                // (Lógica para regenerar JSON si la usas)
                
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al actualizar BD"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "No eres el dueño de este artículo"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Artículo no encontrado"]);
    }
}
?>