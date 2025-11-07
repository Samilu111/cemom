-- Esquema SQL para CEMOM Odontología
CREATE DATABASE IF NOT EXISTS `cemom_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cemom_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255),
  `role` VARCHAR(50) DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `novedades` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  `author_id` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY (`author_id`),
  CONSTRAINT `fk_novedades_author` FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `especialidades` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sucursales` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `direccion` VARCHAR(255),
  `telefono` VARCHAR(50),
  `horario_apertura` TIME NULL,
  `horario_cierre` TIME NULL,
  `lat` DOUBLE NULL,
  `lng` DOUBLE NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `odontologos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(255) NOT NULL,
  `apellido` VARCHAR(255) NOT NULL,
  `matricula` VARCHAR(100),
  `foto` VARCHAR(255),
  `especialidad_id` INT NULL,
  `sucursal_id` INT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY (`especialidad_id`),
  KEY (`sucursal_id`),
  CONSTRAINT `fk_odontologos_especialidad` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_odontologos_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sobre` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `historia` TEXT,
  `objetivos` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `contacto` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `emergencia` VARCHAR(255),
  `otras` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Fin del esquema
