<?php
session_start();

// 1. PRIMERO: Configurar la conexión (esto debe ir antes de cualquier exit)
$host = 'localhost';
$db   = 'bdtruechange'; 
$user = 'root';      
$pass = '';   
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Error de conexión: " . $e->getMessage());
}
if (isset($_POST['id_vendedor'])) {
    
    // 3. Limpiar y recibir el dato
    $idVendedor = htmlspecialchars($_POST['id_vendedor']);

    // 4. Guardarlo en la sesión para que chat.php sepa con quién hablar
    $_SESSION['vendedor_chat_id'] = $idVendedor;

    // 5. Opcional: Responder algo para que el JS sepa que llegó bien
    echo "ID " . $idVendedor . " recibido y guardado en la sesión.";
}
// 2. Definir quién soy YO (el usuario logueado)
if(isset($_SESSION['id_usuario'])){
    if (!defined('CURRENT_USER_ID')) {
        define('CURRENT_USER_ID', $_SESSION['id_usuario']);
    }
}


 
// Usuario B (el con el que se está chateando, debe ser pasado dinámicamente)
// En db_config.php busca la línea 40 y asegúrate de que esté así:
$vendedor_chat_id = isset($_SESSION['vendedor_chat_id']) ? $_SESSION['vendedor_chat_id'] : null;
define('PARTNER_ID', $vendedor_chat_id); 

?>

