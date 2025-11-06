-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 01:20 PM
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
  `calle` varchar(64) NOT NULL,
  `num_calle` varchar(64) NOT NULL,
  `departamento` varchar(64) NOT NULL,
  `gmail_admin` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrador`
--

INSERT INTO `administrador` (`id_admin`, `calle`, `num_calle`, `departamento`, `gmail_admin`) VALUES
(0, 'b5735e979ac8084fc21d22da1d551cd71591d559a939ea6c135bf79d935cb649', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '0745ed362c9a0b77fb2dab6c377ab6bb8650e4cb14d5eda01ca7d7b5d57c65da', '913ef45dd4e1f647359a846bca8bffb8d25b22f2a79d34d71c9c90ef0eb53024'),
(0, 'b5735e979ac8084fc21d22da1d551cd71591d559a939ea6c135bf79d935cb649', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '0745ed362c9a0b77fb2dab6c377ab6bb8650e4cb14d5eda01ca7d7b5d57c65da', 'f17cfe114582d87ab4a930b169579325e8b2cf1af0f951e476a3cc02fff16fb9');

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
  `gmail_usuario` varchar(64) NOT NULL,
  `id_juego` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `puntos` int(10) NOT NULL
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

--
-- Dumping data for table `juego`
--

INSERT INTO `juego` (`id_juego`, `nom_juego`, `desc_juego`) VALUES
(1, 'memory', 'juego de emparejar cartas'),
(2, 'Buscaminas', 'Juego de encontrar todas las minas');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `gmail_usuario` varchar(64) NOT NULL,
  `imagen_perfil` varchar(256) DEFAULT 'usuario.png',
  `iniciar` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nom_usuario`, `passwd`, `gmail_usuario`, `imagen_perfil`, `iniciar`) VALUES
(45, 'prueba', '655e786674d9d3e77bc05ed1de37b4b6bc89f788829f9f3c679e7687b410c89b', '913ef45dd4e1f647359a846bca8bffb8d25b22f2a79d34d71c9c90ef0eb53024', 'usuario.png', 0),
(46, 'flan', 'a7a3a3e9654da0583b8bdec4af14800aa3a4fe6d09d2f059dfb936787cb6ebf0', 'f17cfe114582d87ab4a930b169579325e8b2cf1af0f951e476a3cc02fff16fb9', 'usuario.png', 1),
(47, '111', 'f6e0a1e2ac41945a9aa7ff8a8aaa0cebc12a3bcc981a929ad5cf810a090e11ae', '668caacd70e3dabd3fb5beec642d23505814778aa9d66eea6952737ba22044c8', 'usuario.png', 1),
(48, '222', '9b871512327c09ce91dd649b3f96a63b7408ef267c8cc5710114e629730cb61f', 'b8a9876a45fec4170e98ffcbf3ff25f1ace7c8e533706c3ce36bdad2e047e8bc', 'usuario.png', 1),
(49, '333', '556d7dc3a115356350f1f9910b1af1ab0e312d4b3e4fc788d2da63668f36d017', '672f4341f9c88434f4d8415b7fc8869515640ace256ea5b93676c574b1c88da5', 'usuario.png', 1),
(50, '444', '3538a1ef2e113da64249eea7bd068b585ec7ce5df73b2d1e319d8c9bf47eb314', 'bba4ddb815ba19edff67538226c0ecb18a14fac26a8a712737559161ff575e6a', 'usuario.png', 1),
(51, '555', '91a73fd806ab2c005c13b4dc19130a884e909dea3f72d46e30266fe1a1f588d8', '5e92c7734ea0532461f81236396df1b498e83c3b7176371f882728cc0586c99e', 'usuario.png', 1),
(52, '666', 'c7e616822f366fb1b5e0756af498cc11d2c0862edcb32ca65882f622ff39de1b', '8832d2d623af6ca2f057491f2ca726da1c8cf02a4e66addc2390bb3fe6e7e73f', 'usuario.png', 1);

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
  ADD PRIMARY KEY (`id_juego`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

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
-- AUTO_INCREMENT for table `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
