-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para bdtruechange
CREATE DATABASE IF NOT EXISTS `bdtruechange` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `bdtruechange`;

-- Volcando estructura para tabla bdtruechange.articulos
CREATE TABLE IF NOT EXISTS `articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `estado` enum('nuevo','como nuevo','usado','deteriorado') DEFAULT 'usado',
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estadoArticulo` enum('disponible','reservado','vendido') DEFAULT 'disponible',
  `receptor_id` int(11) DEFAULT NULL,
  `cambio` varchar(100) DEFAULT 'Escucho posibles cambios',
  `fecha_venta` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fk_receptor` (`receptor_id`),
  CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_receptor` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.articulos: ~1 rows (aproximadamente)
INSERT INTO `articulos` (`id`, `usuario_id`, `titulo`, `descripcion`, `categoria`, `estado`, `fecha_publicacion`, `estadoArticulo`, `receptor_id`, `cambio`, `fecha_venta`) VALUES
	(102, 33, 'Patinete eléctrico', 'Patinete eléctrico en muy buen estado.\r\n- Batería al 100% de capacidad.\r\n- Ruedas en buen estado.\r\n- Sin roces ni caídas.', 'Tecnología y electrónica', 'como nuevo', '2026-04-02 11:16:59', 'vendido', 35, 'Nintendo switch 2', '2026-04-04 10:01:42');

-- Volcando estructura para tabla bdtruechange.articulos_fotos
CREATE TABLE IF NOT EXISTS `articulos_fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) NOT NULL,
  `ruta_foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `articulos_fotos_ibfk_1` (`articulo_id`),
  CONSTRAINT `articulos_fotos_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.articulos_fotos: ~0 rows (aproximadamente)
INSERT INTO `articulos_fotos` (`id`, `articulo_id`, `ruta_foto`) VALUES
	(87, 102, '../imagenes/uploads/69ce502bd4c4b.jpg');

-- Volcando estructura para tabla bdtruechange.favoritos
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `fecha_guardado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_usuario` (`id_usuario`,`id_articulo`),
  KEY `id_articulo` (`id_articulo`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.favoritos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdtruechange.intercambios
CREATE TABLE IF NOT EXISTS `intercambios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitante_id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `articulo_solicitante` int(11) NOT NULL,
  `articulo_receptor` int(11) NOT NULL,
  `estado` enum('pendiente','aceptado','rechazado','cancelado','completado') DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `solicitante_id` (`solicitante_id`),
  KEY `receptor_id` (`receptor_id`),
  KEY `articulo_solicitante` (`articulo_solicitante`),
  KEY `articulo_receptor` (`articulo_receptor`),
  CONSTRAINT `intercambios_ibfk_1` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercambios_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercambios_ibfk_3` FOREIGN KEY (`articulo_solicitante`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercambios_ibfk_4` FOREIGN KEY (`articulo_receptor`) REFERENCES `articulos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.intercambios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdtruechange.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=265 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.messages: ~3 rows (aproximadamente)
INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message_text`, `timestamp`, `is_read`) VALUES
	(262, 35, 33, 'Buenos días José Luis me interesaría el patinete que tienes anunciado.', '2026-04-04 11:28:42', 1),
	(263, 35, 33, 'Lo cambiarías por una Nintendo Switch que tengo anunciada? Gracias. Un saludo.', '2026-04-04 11:29:56', 1),
	(264, 33, 35, 'Hola Antonio. Si, genial!! Si quieres podemos quedar. Gracias', '2026-04-04 11:49:07', 1);

-- Volcando estructura para tabla bdtruechange.reseñas
CREATE TABLE IF NOT EXISTS `reseñas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) DEFAULT NULL,
  `emisor_id` int(11) DEFAULT NULL,
  `receptor_id` int(11) DEFAULT NULL,
  `puntuacion` int(11) DEFAULT NULL CHECK (`puntuacion` >= 1 and `puntuacion` <= 5),
  `comentario` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `articulo_id` (`articulo_id`),
  KEY `emisor_id` (`emisor_id`),
  KEY `receptor_id` (`receptor_id`),
  CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`),
  CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`emisor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `reseñas_ibfk_3` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.reseñas: ~0 rows (aproximadamente)
INSERT INTO `reseñas` (`id`, `articulo_id`, `emisor_id`, `receptor_id`, `puntuacion`, `comentario`, `fecha`) VALUES
	(8, 102, 33, 35, 5, 'Todo perfecto. Muy recomendable. Puntual y honesto.', '2026-04-04 12:07:41');

-- Volcando estructura para tabla bdtruechange.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(150) NOT NULL,
  `apellido2` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'imagenes/uploads/default.png',
  `ciudad` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuarioNombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `usuarioNombre` (`usuarioNombre`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `apellido1`, `apellido2`, `email`, `password`, `avatar`, `ciudad`, `fecha_registro`, `usuarioNombre`) VALUES
	(33, 'Jose Luis', 'Ugalde', 'Mora', 'joselulrd@gmail.com', '$2y$10$NcsAuHf755lj16kAG9Z8aOJ7BC02Tb1CyfP82S0pzm6yFa1DSXnsC', '../imagenes/uploadspexels-man-1281562_1920.jpg', 'Logroño', '2026-01-17 09:07:00', 'Jose1234'),
	(35, 'Antonio', 'Ugalde', 'Mora', 'antoniod@gmail.com', '$2y$10$NcsAuHf755lj16kAG9Z8aOJ7BC02Tb1CyfP82S0pzm6yFa1DSXnsC', '../imagenes/uploads/default.png', 'Vitoria', '2026-01-17 09:07:00', 'Antonio1234');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
