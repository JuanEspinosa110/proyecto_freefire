-- Reset y creación de tablas relacionadas con Salas
-- Ejecutar en phpMyAdmin o desde la consola MySQL conectada a la base 'free_fire'

DROP TABLE IF EXISTS sala_jugadores;
DROP TABLE IF EXISTS sala;
DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS personajes;
DROP TABLE IF EXISTS niveles;
DROP TABLE IF EXISTS mapa;
DROP TABLE IF EXISTS modos_juegos;
DROP TABLE IF EXISTS estado;

-- Crear tablas base (mínimas necesarias)
CREATE TABLE niveles (
  id_niveles INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

CREATE TABLE mapa (
  id_mapa INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  imagen VARCHAR(255) DEFAULT 'IMG/fondo.jpg'
);

CREATE TABLE modos_juegos (
  id_modo_juegos INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

CREATE TABLE estado (
  id_estado INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

CREATE TABLE personajes (
  Id_personajes INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  skin VARCHAR(255) DEFAULT 'IMG/default_skin.png',
  descripcion TEXT
);

CREATE TABLE usuario (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  correo VARCHAR(150),
  puntos INT DEFAULT 0,
  id_niveles INT DEFAULT 1,
  Id_personajes INT DEFAULT 1,
  id_estado INT DEFAULT 1,
  ultima_conexion DATETIME NULL,
  FOREIGN KEY (id_niveles) REFERENCES niveles(id_niveles),
  FOREIGN KEY (Id_personajes) REFERENCES personajes(Id_personajes)
);

CREATE TABLE sala (
  id_sala INT AUTO_INCREMENT PRIMARY KEY,
  id_modo_juegos INT NOT NULL,
  id_niveles INT NOT NULL,
  id_mapa INT NOT NULL,
  id_estado INT NOT NULL DEFAULT 1,
  jugadores_actuales INT DEFAULT 0,
  max_jugadores INT DEFAULT 2,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_modo_juegos) REFERENCES modos_juegos(id_modo_juegos),
  FOREIGN KEY (id_niveles) REFERENCES niveles(id_niveles),
  FOREIGN KEY (id_mapa) REFERENCES mapa(id_mapa),
  FOREIGN KEY (id_estado) REFERENCES estado(id_estado)
);

CREATE TABLE sala_jugadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_sala INT NOT NULL,
  id_user INT NOT NULL,
  eliminado TINYINT DEFAULT 0,
  fecha_ingreso TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_sala) REFERENCES sala(id_sala) ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES usuario(id_user) ON DELETE CASCADE
);

-- Datos iniciales ejemplo (mapas con rutas a IMG/)
INSERT INTO niveles (nombre) VALUES ('Bronce'), ('Plata'), ('Oro');
INSERT INTO mapa (nombre, imagen) VALUES
  ('Bermuda', 'IMG/bermuda.jpg'),
  ('Purgatorio', 'IMG/purgatorio.jpg'),
  ('Kalahary', 'IMG/kalahary.png');
INSERT INTO modos_juegos (nombre) VALUES ('Clásico'), ('Dúo'), ('Escuadra');
INSERT INTO estado (nombre) VALUES ('Activa'), ('En Espera'), ('Finalizada');

INSERT INTO personajes (nombre, skin, descripcion) VALUES
  ('Adam', 'IMG/adam.png', 'Personaje inicial'),
  ('Eve', 'IMG/eve.png', 'Personaje ágil');

-- Usuario admin de prueba (contrasena: admin123)
INSERT INTO usuario (username, contrasena, correo, puntos, id_niveles, Id_personajes, id_estado)
VALUES ('admin', MD5('admin123'), 'admin@freefire.com', 0, 1, 1, 1);

-- Nota: ejecutar este script reseteará las tablas listadas. Haz backup si lo necesitas.
