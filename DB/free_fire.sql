-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 12:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `free_fire`
--

-- --------------------------------------------------------

--
-- Table structure for table `armas`
--

CREATE TABLE `armas` (
  `id_armas` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `dano_cabeza` int(11) NOT NULL,
  `dano_cuerpo` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `id_tipo_arma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `armas`
--

INSERT INTO `armas` (`id_armas`, `nombre`, `dano_cabeza`, `dano_cuerpo`, `imagen`, `id_tipo_arma`) VALUES
(9, 'Puño', 5, 3, 'IMG/puño.png', 1),
(10, 'Katana', 12, 6, 'IMG/katana.png', 1),
(11, 'G18', 15, 10, 'IMG/g18.png', 2),
(12, 'Desert Eagle', 20, 15, 'IMG/desert_eagle.png', 2),
(13, 'AWM', 75, 50, 'IMG/awm.png', 3),
(14, 'M82B', 75, 50, 'IMG/m82b.png', 3),
(15, 'M249', 50, 40, 'IMG/m249.png', 4),
(16, 'KORD', 50, 40, 'IMG/kord.png', 4);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_partida`
--

CREATE TABLE `detalle_partida` (
  `id_detalle_partida` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_partidas` int(11) NOT NULL,
  `id_armas` int(11) NOT NULL,
  `dano_causado` int(11) NOT NULL,
  `dano_recibido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre`) VALUES
(1, 'activo'),
(2, 'bloqueado'),
(3, 'en juego'),
(4, 'en espera'),
(5, 'llena');

-- --------------------------------------------------------

--
-- Table structure for table `id_tip_user`
--

CREATE TABLE `id_tip_user` (
  `id_tip_user` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `id_tip_user`
--

INSERT INTO `id_tip_user` (`id_tip_user`, `tipo`) VALUES
(1, 'admin'),
(2, 'jugador');

-- --------------------------------------------------------

--
-- Table structure for table `mapa`
--

CREATE TABLE `mapa` (
  `id_mapa` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `imagen` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapa`
--

INSERT INTO `mapa` (`id_mapa`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'Bermuda', 'Una isla inspirada en el Triángulo de las Bermudas, que comb', '/img/mapas/bermuda.jpg'),
(2, 'Purgatorio', 'Mapa grande con un terreno variado de montañas, lagos y un r', '/img/mapas/purgatorio.jpg'),
(3, 'kalahari', 'Mapa desértico caracterizado por su terreno rojizo y estruct', '/img/mapas/kalahari.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `modos_juegos`
--

CREATE TABLE `modos_juegos` (
  `id_modo_juegos` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modos_juegos`
--

INSERT INTO `modos_juegos` (`id_modo_juegos`, `nombre`, `descripcion`, `tipo`) VALUES
(1, 'BR-Clasificatoria', 'Mapa abierto de maximo 5 jugadores ', NULL),
(2, 'DE-Clasificatoria', '4v4 en mapas pequeños', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `niveles`
--

CREATE TABLE `niveles` (
  `id_niveles` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `niveles`
--

INSERT INTO `niveles` (`id_niveles`, `nombre`) VALUES
(1, 'oro'),
(2, 'plantino'),
(3, 'diamante'),
(4, 'heroico'),
(5, 'maestro');

-- --------------------------------------------------------

--
-- Table structure for table `partidas`
--

CREATE TABLE `partidas` (
  `id_partida` int(11) NOT NULL,
  `id_sala` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `Ganador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personajes`
--

CREATE TABLE `personajes` (
  `Id_personajes` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `skin` varchar(250) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personajes`
--

INSERT INTO `personajes` (`Id_personajes`, `nombre`, `skin`, `descripcion`) VALUES
(1, 'alok', 'IMG/personaje_alok.png', 'Alok, es un personaje de apoyo con la habilidad \"Ritmo brutal\", que crea un aura que aumenta la velocidad de movimiento y restaura pv para el y sus compañeros de equipo. Su nombre significa \"luz\" y vino al juego para dar un concierto especial.'),
(2, 'kapella', 'IMG/personaje_kapella.png\r\n', 'Kapella es una cantante de pop de garena free fire, conocida por su habilidad especial llamada cancion curativa, que aumenta los efectos de los objetos y habilidades de curacion y reduce la perdida de PV de los aliados.\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `sala`
--

CREATE TABLE `sala` (
  `id_sala` int(11) NOT NULL,
  `id_modo_juegos` int(11) DEFAULT NULL,
  `id_niveles` int(11) DEFAULT NULL,
  `id_mapa` int(11) NOT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `jugadores_actuales` int(11) DEFAULT 1,
  `max_jugadores` int(11) DEFAULT 5,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sala_jugadores`
--

CREATE TABLE `sala_jugadores` (
  `id_sala_jugadores` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `fecha_ingreso` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipo_armas`
--

CREATE TABLE `tipo_armas` (
  `id_tipo_arma` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipo_armas`
--

INSERT INTO `tipo_armas` (`id_tipo_arma`, `nombre`, `tipo`) VALUES
(1, 'Puño', 'Cuerpo a cuerpo'),
(2, 'Pistola', 'Corta distancia'),
(3, 'Francotirador', 'Larga distancia'),
(4, 'Ametralladora', 'Pesada');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `nombre` text NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `puntos` int(11) DEFAULT 0,
  `id_niveles` int(11) DEFAULT NULL,
  `id_tip_user` int(11) DEFAULT NULL,
  `Id_personajes` int(11) NOT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `ultima_conexion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id_user`, `username`, `nombre`, `correo`, `contrasena`, `puntos`, `id_niveles`, `id_tip_user`, `Id_personajes`, `id_estado`, `ultima_conexion`) VALUES
(312123231, 'dd', '1231231', 'asda@gmail.com', '$2y$10$W2c/L7WX7910AVJrZlP.QOKdAQ.ch1.8gH.611YoZh4fWRdmNkSNi', 0, 1, 2, 1, 1, '2025-10-08 21:06:58'),
(1110495789, 'dires123', 'Didier Reyes', 'didierreyes003@gmail.com', '$2y$10$qOd5sC/ZP4ag01dLDO0/QuhJ4JD1muGxZpZVXeArdp9hi9FcndJ4K', 0, 1, 1, 2, 1, '2025-10-08 20:12:40'),
(1121212312, 'juanito', 'juan', 'reyesz2803@gmail.com', '$2y$10$Gp4Wez/J8ys8gboVx0yPiOfVgp5oeuuVrupeeOlssQ4B3ruv/Y/J6', 0, 1, 2, 1, 2, '2025-10-08 20:36:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `armas`
--
ALTER TABLE `armas`
  ADD PRIMARY KEY (`id_armas`),
  ADD KEY `id_tipo_arma` (`id_tipo_arma`);

--
-- Indexes for table `detalle_partida`
--
ALTER TABLE `detalle_partida`
  ADD PRIMARY KEY (`id_detalle_partida`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_partidas` (`id_partidas`),
  ADD KEY `id_armas` (`id_armas`);

--
-- Indexes for table `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `id_tip_user`
--
ALTER TABLE `id_tip_user`
  ADD PRIMARY KEY (`id_tip_user`);

--
-- Indexes for table `mapa`
--
ALTER TABLE `mapa`
  ADD PRIMARY KEY (`id_mapa`);

--
-- Indexes for table `modos_juegos`
--
ALTER TABLE `modos_juegos`
  ADD PRIMARY KEY (`id_modo_juegos`);

--
-- Indexes for table `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id_niveles`);

--
-- Indexes for table `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indexes for table `personajes`
--
ALTER TABLE `personajes`
  ADD PRIMARY KEY (`Id_personajes`);

--
-- Indexes for table `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`id_sala`),
  ADD KEY `id_modo_juegos` (`id_modo_juegos`),
  ADD KEY `id_niveles` (`id_niveles`),
  ADD KEY `id_mapa` (`id_mapa`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indexes for table `sala_jugadores`
--
ALTER TABLE `sala_jugadores`
  ADD PRIMARY KEY (`id_sala_jugadores`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indexes for table `tipo_armas`
--
ALTER TABLE `tipo_armas`
  ADD PRIMARY KEY (`id_tipo_arma`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_niveles` (`id_niveles`),
  ADD KEY `id_tip_user` (`id_tip_user`),
  ADD KEY `Id_personajes` (`Id_personajes`),
  ADD KEY `id_estado` (`id_estado`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `armas`
--
ALTER TABLE `armas`
  MODIFY `id_armas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `id_tip_user`
--
ALTER TABLE `id_tip_user`
  MODIFY `id_tip_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `modos_juegos`
--
ALTER TABLE `modos_juegos`
  MODIFY `id_modo_juegos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id_niveles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sala`
--
ALTER TABLE `sala`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sala_jugadores`
--
ALTER TABLE `sala_jugadores`
  MODIFY `id_sala_jugadores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tipo_armas`
--
ALTER TABLE `tipo_armas`
  MODIFY `id_tipo_arma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1121212313;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_partida`
--
ALTER TABLE `detalle_partida`
  ADD CONSTRAINT `detalle_partida_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id_user`),
  ADD CONSTRAINT `detalle_partida_ibfk_2` FOREIGN KEY (`id_partidas`) REFERENCES `partidas` (`id_partida`),
  ADD CONSTRAINT `detalle_partida_ibfk_3` FOREIGN KEY (`id_armas`) REFERENCES `armas` (`id_armas`);

--
-- Constraints for table `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`);

--
-- Constraints for table `sala`
--
ALTER TABLE `sala`
  ADD CONSTRAINT `sala_ibfk_1` FOREIGN KEY (`id_modo_juegos`) REFERENCES `modos_juegos` (`id_modo_juegos`),
  ADD CONSTRAINT `sala_ibfk_2` FOREIGN KEY (`id_niveles`) REFERENCES `niveles` (`id_niveles`),
  ADD CONSTRAINT `sala_ibfk_3` FOREIGN KEY (`id_mapa`) REFERENCES `mapa` (`id_mapa`),
  ADD CONSTRAINT `sala_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Constraints for table `sala_jugadores`
--
ALTER TABLE `sala_jugadores`
  ADD CONSTRAINT `sala_jugadores_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuario` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sala_jugadores_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_tip_user`) REFERENCES `id_tip_user` (`id_tip_user`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_niveles`) REFERENCES `niveles` (`id_niveles`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`Id_personajes`) REFERENCES `personajes` (`Id_personajes`),
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
