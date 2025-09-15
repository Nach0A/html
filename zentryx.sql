-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 01:41 PM
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
-- Database: `zentryx`
--

-- --------------------------------------------------------

--
-- Table structure for table `administra`
--

CREATE TABLE `administra` (
  `id_admin` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `id_grupo` int(100) NOT NULL,
  `gmail_admin` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `passwd_admin` varchar(64) NOT NULL,
  `calle` varchar(64) NOT NULL,
  `num_calle` varchar(64) NOT NULL,
  `departamento` varchar(64) NOT NULL,
  `gmail_admin` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `administrador_tel`
--

CREATE TABLE `administrador_tel` (
  `id_admin` int(100) NOT NULL,
  `tel` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `gmail_usuario` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habla`
--

CREATE TABLE `habla` (
  `id_usuario_emisor` int(100) NOT NULL,
  `id_usuario_receptor` int(100) NOT NULL,
  `contenido` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `juega`
--

CREATE TABLE `juega` (
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `gmail_usuario` varchar(64) NOT NULL,
  `id_juego` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `juego`
--

CREATE TABLE `juego` (
  `id_juego` int(100) NOT NULL,
  `nom_juego` varchar(64) NOT NULL,
  `desc_juego` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `gmail_usuario` varchar(64) NOT NULL,
  `imagen_perfil` varchar(256) DEFAULT 'usuario.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nom_usuario`, `passwd`, `gmail_usuario`, `imagen_perfil`) VALUES
(20, 'juan', 'ed08c290d7e22f7bb324b15cbadce35b0b348564fd2d5f95752388d86d71bcca', 'c21de19190f725e92595482548338a79f86e108285678731010989a866684b4f', 'usuario.png'),
(22, '111', '5477957537', '1234314', 'usuario.png'),
(24, 'papu', '72beaafbd6684b693da9e80c39f5f0244a8042f6371a46d95e69b05ba638f198', 'papu@gmail.com', 'usuario.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administra`
--
ALTER TABLE `administra`
  ADD PRIMARY KEY (`id_admin`,`id_usuario`,`id_grupo`,`gmail_admin`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indexes for table `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`,`gmail_admin`);

--
-- Indexes for table `administrador_tel`
--
ALTER TABLE `administrador_tel`
  ADD PRIMARY KEY (`id_admin`,`tel`);

--
-- Indexes for table `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`,`id_usuario`,`nom_usuario`,`gmail_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `habla`
--
ALTER TABLE `habla`
  ADD PRIMARY KEY (`id_usuario_emisor`,`id_usuario_receptor`),
  ADD KEY `id_usuario_receptor` (`id_usuario_receptor`);

--
-- Indexes for table `juega`
--
ALTER TABLE `juega`
  ADD PRIMARY KEY (`id_usuario`,`nom_usuario`,`gmail_usuario`,`id_juego`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indexes for table `juego`
--
ALTER TABLE `juego`
  ADD PRIMARY KEY (`id_juego`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`,`nom_usuario`,`gmail_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrador_tel`
--
ALTER TABLE `administrador_tel`
  MODIFY `id_admin` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `juega`
--
ALTER TABLE `juega`
  MODIFY `id_usuario` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administra`
--
ALTER TABLE `administra`
  ADD CONSTRAINT `administra_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`),
  ADD CONSTRAINT `administra_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `administra_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Constraints for table `administrador_tel`
--
ALTER TABLE `administrador_tel`
  ADD CONSTRAINT `administrador_tel_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`);

--
-- Constraints for table `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `habla`
--
ALTER TABLE `habla`
  ADD CONSTRAINT `habla_ibfk_1` FOREIGN KEY (`id_usuario_emisor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `habla_ibfk_2` FOREIGN KEY (`id_usuario_receptor`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `juega`
--
ALTER TABLE `juega`
  ADD CONSTRAINT `juega_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `juega_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
