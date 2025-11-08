-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2025 a las 01:04:27
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zentryx`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administra`
--

CREATE TABLE `administra` (
  `id_admin` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `id_grupo` int(100) NOT NULL,
  `gmail_admin` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `calle` varchar(64) NOT NULL,
  `num_calle` varchar(64) NOT NULL,
  `departamento` varchar(64) NOT NULL,
  `gmail_admin` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `calle`, `num_calle`, `departamento`, `gmail_admin`) VALUES
(0, 'b5735e979ac8084fc21d22da1d551cd71591d559a939ea6c135bf79d935cb649', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '0745ed362c9a0b77fb2dab6c377ab6bb8650e4cb14d5eda01ca7d7b5d57c65da', '668caacd70e3dabd3fb5beec642d23505814778aa9d66eea6952737ba22044c8');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador_tel`
--

CREATE TABLE `administrador_tel` (
  `id_admin` int(100) NOT NULL,
  `tel` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `gmail_usuario` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habla`
--

CREATE TABLE `habla` (
  `id_usuario_emisor` int(100) NOT NULL,
  `id_usuario_receptor` int(100) NOT NULL,
  `contenido` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juega`
--

CREATE TABLE `juega` (
  `gmail_usuario` varchar(64) NOT NULL,
  `id_juego` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `nom_usuario` varchar(64) NOT NULL,
  `puntos` int(10) NOT NULL,
  `tiempo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juega`
--

INSERT INTO `juega` (`gmail_usuario`, `id_juego`, `id_usuario`, `nom_usuario`, `puntos`, `tiempo`) VALUES
('bba4ddb815ba19edff67538226c0ecb18a14fac26a8a712737559161ff575e6a', 1, 53, '444', 2386, 0),
('668caacd70e3dabd3fb5beec642d23505814778aa9d66eea6952737ba22044c8', 1, 55, '111', 2491, 55);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego`
--

CREATE TABLE `juego` (
  `id_juego` int(100) NOT NULL,
  `nom_juego` varchar(64) NOT NULL,
  `desc_juego` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juego`
--

INSERT INTO `juego` (`id_juego`, `nom_juego`, `desc_juego`) VALUES
(1, 'memory', 'juego de emparejar cartas'),
(2, 'Buscaminas', 'Juego de encontrar todas las minas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
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
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nom_usuario`, `passwd`, `gmail_usuario`, `imagen_perfil`, `iniciar`) VALUES
(56, '555', '91a73fd806ab2c005c13b4dc19130a884e909dea3f72d46e30266fe1a1f588d8', '5e92c7734ea0532461f81236396df1b498e83c3b7176371f882728cc0586c99e', 'usuario.png', 0),
(57, '777', 'eaf89db7108470dc3f6b23ea90618264b3e8f8b6145371667c4055e9c5ce9f52', '626b2fe62f7bef6c8a4f86bafdf7e26a4c4804cbfd6cb975cc0276f415b89a44', 'usuario.png', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administra`
--
ALTER TABLE `administra`
  ADD PRIMARY KEY (`id_admin`,`id_usuario`,`id_grupo`,`gmail_admin`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`,`gmail_admin`);

--
-- Indices de la tabla `administrador_tel`
--
ALTER TABLE `administrador_tel`
  ADD PRIMARY KEY (`id_admin`,`tel`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`,`id_usuario`,`nom_usuario`,`gmail_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `habla`
--
ALTER TABLE `habla`
  ADD PRIMARY KEY (`id_usuario_emisor`,`id_usuario_receptor`),
  ADD KEY `id_usuario_receptor` (`id_usuario_receptor`);

--
-- Indices de la tabla `juega`
--
ALTER TABLE `juega`
  ADD PRIMARY KEY (`id_juego`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `juego`
--
ALTER TABLE `juego`
  ADD PRIMARY KEY (`id_juego`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`,`nom_usuario`,`gmail_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador_tel`
--
ALTER TABLE `administrador_tel`
  MODIFY `id_admin` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administra`
--
ALTER TABLE `administra`
  ADD CONSTRAINT `administra_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`),
  ADD CONSTRAINT `administra_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `administra_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `administrador_tel`
--
ALTER TABLE `administrador_tel`
  ADD CONSTRAINT `administrador_tel_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrador` (`id_admin`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `habla`
--
ALTER TABLE `habla`
  ADD CONSTRAINT `habla_ibfk_1` FOREIGN KEY (`id_usuario_emisor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `habla_ibfk_2` FOREIGN KEY (`id_usuario_receptor`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `juega`
--
ALTER TABLE `juega`
  ADD CONSTRAINT `juega_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `juega_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
