<?php
// public/cambiar_estado.php
require_once "Usuario.php";
require_once "../src/crearFicheroJson.php";
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_usuario'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    $idArticulo = $_POST['id'];
    $nuevoEstado = $_POST['estadoArticulo'];
    $idUsuario = $_SESSION['id_usuario'];

    // --- CAMBIO 1: Recoger el comprador (si no viene, es null) ---
    $compradorId = !empty($_POST['comprador_id']) ? $_POST['comprador_id'] : null;

    // --- CAMBIO 2: Añadir 'comprador_id = ?' a la consulta ---
    $query = "UPDATE articulos SET estadoArticulo = ?, comprador_id = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $db->prepare($query);
    
    // --- CAMBIO 3: Añadir $compradorId al array de ejecución ---
    if ($stmt->execute([$nuevoEstado, $compradorId, $idArticulo, $idUsuario])) {

    // 1. Verificar que el artículo pertenece al usuario logueado
    $query = "UPDATE articulos SET estadoArticulo = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$nuevoEstado, $idArticulo, $idUsuario])) {
        // 2. Regenerar los ficheros JSON para que el cambio se vea en la web
        $usuarioObj = new Usuario($database);
        creaYactualiza($usuarioObj); 
        
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo actualizar"]);
    }
}
}