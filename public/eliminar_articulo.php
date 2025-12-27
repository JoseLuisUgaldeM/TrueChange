<?php
session_start();
require_once("../config/Database.php"); // Ajusta la ruta si es necesario

if (!isset($_SESSION['id_usuario']) || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$id_articulo = $_POST['id'];
$id_usuario = $_SESSION['id_usuario'];

try {
    // IMPORTANTE: Verificamos que el artículo pertenezca al usuario antes de borrar (seguridad)
    $sql = "DELETE FROM articulos WHERE id = :id_articulo AND usuario_id = :id_usuario";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id_articulo' => $id_articulo, ':id_usuario' => $id_usuario]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo borrar o no es tuyo']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>