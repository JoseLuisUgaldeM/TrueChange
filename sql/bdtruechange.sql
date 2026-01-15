
CREATE DATABASE IF NOT EXISTS `bdtruechange` ;
USE `bdtruechange`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(150) NOT NULL,
  `apellido2` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'imagenes/uploads/default.png',
  `ciudad` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `estado` enum('nuevo','como nuevo','usado','deteriorado') DEFAULT 'usado',
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estadoArticulo` enum('disponible','reservado','vendido') DEFAULT 'disponible',
  `comprador_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fk_comprador` (`comprador_id`),
  CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comprador` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `articulos_fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) NOT NULL,
  `ruta_foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `articulos_fotos_ibfk_1` (`articulo_id`),
  CONSTRAINT `articulos_fotos_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `contenido` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

