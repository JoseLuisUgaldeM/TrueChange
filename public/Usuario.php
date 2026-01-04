<?php

require "../config/Database.php";
// User class
class Usuario {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->getConnection();
    }

    public function crearUsuario($nombre, $apellido1, $apellido2, $email, $password, $ciudad) {
        $sql = "INSERT INTO usuarios (nombre, apellido1, apellido2, email, password, ciudad) VALUES (:nombre, :apellido1, :apellido2, :email, :password, :ciudad)";
        $stmt = $this->db->prepare($sql); 
        return $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':apellido1' => $apellido1,
            ':apellido2' => $apellido2,
            ':ciudad' => $ciudad

        ]);
    }

    public function obtenerUsuarioPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

       public function cambiarAvatar($id_usuario, $avatar) {
        $sql = "UPDATE usuarios SET avatar = :avatar WHERE id = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario, ':avatar'=> $avatar]);
        echo $avatar;
        return $stmt->fetch();
    }

     public function obtenerUsuarioPorId($id_usuario) {
        $sql = "SELECT * FROM usuarios WHERE id = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        return $stmt->fetch();
    }

    public function listarUsuarios() {
        $sql = "SELECT id, nombre, email FROM usuarios";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

     public function login($nombre, $password) {

        $sql = "SELECT id, nombre, password FROM usuarios WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":nombre" => $nombre]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario["password"])) {

        
            return $usuario; // devuelve datos del usuario
            
        }
        
        return false; // credenciales inválidas
    }

public function crearFichero() {
    // Añadimos una subconsulta para calcular la media de reseñas del dueño del artículo
    // COALESCE(..., 0) sirve para que si no tiene reseñas, devuelva 0 en lugar de nada (NULL)
    $sql = 'SELECT *, 
            (SELECT COALESCE(AVG(puntuacion), 0) 
             FROM reseñas 
             WHERE receptor_id = usuarios.id) as valoracion_media
            FROM usuarios
            INNER JOIN articulos ON articulos.usuario_id = usuarios.id 
            INNER JOIN articulos_fotos ON articulos_fotos.articulo_id = articulos.id';

    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
         public function crearFicheroMisProductos($id){

            $sql = 'SELECT *  from usuarios
            INNER JOIN articulos ON articulos.usuario_id = usuarios.id 
            INNER JOIN articulos_fotos ON articulos_fotos.articulo_id = articulos.id WHERE usuarios.id = :id 
            ';

        $stmt = $this->db->prepare($sql);
         $stmt->execute([":id" => $id]);
         return $stmt->fetchAll();

        }
    
    

        public function obtenerValoracionMedia($idUsuario) {
            // Calculamos la media de la columna 'puntuacion' para este usuario
            $sql = "SELECT AVG(puntuacion) as media FROM reseñas WHERE receptor_id = ?";
            $stmt = $this->db->prepare($sql); // Asumiendo que usas $this->conn
            $stmt->execute([$idUsuario]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si no tiene reseñas, devolvemos 0. Si tiene, devolvemos el número con 1 decimal (ej: 4.5)
            return $resultado['media'] ? round($resultado['media'], 1) : 0;
}
}
?>
