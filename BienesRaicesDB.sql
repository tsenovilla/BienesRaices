CREATE TABLE `BLOG` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo_espanol` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `titulo_english` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `autor` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `resumen_espanol` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `resumen_english` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `texto_espanol` longtext CHARACTER SET utf8mb4,
  `texto_english` longtext CHARACTER SET utf8mb4,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `CONTACTO` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `motivo_contacto` varchar(255) DEFAULT NULL,
  `presupuesto` int DEFAULT NULL,
  `mensaje` varchar(255) DEFAULT NULL,
  `preferencia_contacto` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `PROPIEDADES` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo_espanol` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `titulo_english` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `descripcion_espanol` longtext CHARACTER SET utf8mb4 NOT NULL,
  `descripcion_english` longtext CHARACTER SET utf8mb4,
  `dormitorios` int DEFAULT NULL,
  `wc` int DEFAULT NULL,
  `aparcamientos` int DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `id_vendedor` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ID_VENDEDOR` (`id_vendedor`),
  CONSTRAINT `propiedades_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `VENDEDORES` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `USUARIOS` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `office_timezone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `VENDEDORES` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `apellido` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(20) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
