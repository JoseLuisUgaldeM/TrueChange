<?php
// guardar_resena.php
session_start();
require_once "Usuario.php";
$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articulo_id = $_POST['articulo_id'];
    $puntuacion = $_POST['puntuacion'];
    $comentario = $_POST['comentario'];
    $emisor_id = $_SESSION['id_usuario'];

// --- CORRECCIÓN AQUÍ ---
    // Seleccionamos el 'comprador_id' en lugar del 'usuario_id'
    $stmt = $db->prepare("SELECT comprador_id FROM articulos WHERE id = ?");
    $stmt->execute([$articulo_id]);
    $articulo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // El receptor de la reseña es quien compró el artículo
    $receptor_id = $articulo['comprador_id'];

    if (!$receptor_id) {
        echo json_encode(["success" => false, "message" => "Error: No hay comprador asignado a este artículo."]);
        exit;
    }

    // Insertamos la reseña
    $sql = "INSERT INTO reseñas (articulo_id, emisor_id, receptor_id, puntuacion, comentario) VALUES (?, ?, ?, ?, ?)";
    $ins = $db->prepare($sql);
    
    if ($ins->execute([$articulo_id, $emisor_id, $receptor_id, $puntuacion, $comentario])) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar en BD"]);
    }
}
?>