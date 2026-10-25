DROP DATABASE IF EXISTS liga_basquet;
CREATE DATABASE liga_basquet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE liga_basquet;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  ciudad VARCHAR(100) NOT NULL,
  entrenador VARCHAR(100),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jugadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipo_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  numero INT NOT NULL,
  posicion VARCHAR(30) NOT NULL,
  FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
);

CREATE TABLE partidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipo_local_id INT NOT NULL,
  equipo_visitante_id INT NOT NULL,
  puntos_local INT DEFAULT NULL,
  puntos_visitante INT DEFAULT NULL,
  fecha DATE NOT NULL,
  FOREIGN KEY (equipo_local_id) REFERENCES equipos(id) ON DELETE CASCADE,
  FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(id) ON DELETE CASCADE
);

INSERT IGNORE INTO usuarios (nombre, correo, contrasena)
VALUES ('Mario', '123mario@gmail.com.pe', '$2y$10$.77PqahaVqLmgRmdKi8BLOBpmk1wUQMMMKJPIGpi3HzMVAJJtFbOS');