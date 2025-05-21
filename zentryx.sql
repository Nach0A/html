-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-05-2025 a las 14:50:16
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
  `nom_usuario` varchar(20) NOT NULL,
  `id_grupo` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(100) NOT NULL,
  `nom_usuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habla`
--

CREATE TABLE `habla` (
  `nom_usuario_emisor` varchar(20) NOT NULL,
  `nom_usuario_receptor` varchar(20) NOT NULL,
  `id_juego` int(20) NOT NULL,
  `contenido` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juega`
--

CREATE TABLE `juega` (
  `nom_usuario` varchar(20) NOT NULL,
  `id_juego` int(20) NOT NULL,
  `puntos` int(50) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego`
--

CREATE TABLE `juego` (
  `id_juego` int(20) NOT NULL,
  `nom_juego` varchar(20) NOT NULL,
  `reglas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `root`
--

CREATE TABLE `root` (
  `id_root` int(11) NOT NULL,
  `passwd_root` varchar(80) NOT NULL,
  `calle` varchar(20) NOT NULL,
  `num_calle` int(11) NOT NULL,
  `departamento` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `root`
--

INSERT INTO `root` (`id_root`, `passwd_root`, `calle`, `num_calle`, `departamento`) VALUES
(1, '12345678', 'papu', 2, 'papudepartamento'),
(2, '12345678', 'papucalle', 3, 'papudepartamento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `root_tel`
--

CREATE TABLE `root_tel` (
  `id_root` int(11) NOT NULL,
  `tel` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `root_tel`
--

INSERT INTO `root_tel` (`id_root`, `tel`) VALUES
(2, 99444),
(1, 996543);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `nom_usuario` varchar(20) NOT NULL,
  `passwd_usuario` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administra`
--
ALTER TABLE `administra`
  ADD PRIMARY KEY (`nom_usuario`,`id_grupo`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`,`nom_usuario`),
  ADD KEY `nom_usuario` (`nom_usuario`);

--
-- Indices de la tabla `habla`
--
ALTER TABLE `habla`
  ADD PRIMARY KEY (`nom_usuario_emisor`,`nom_usuario_receptor`,`id_juego`),
  ADD KEY `nom_usuario_receptor` (`nom_usuario_receptor`),
  ADD KEY `id_juego` (`id_juego`);

--
-- Indices de la tabla `juega`
--
ALTER TABLE `juega`
  ADD PRIMARY KEY (`id_juego`,`nom_usuario`),
  ADD KEY `nom_usuario` (`nom_usuario`);

--
-- Indices de la tabla `juego`
--
ALTER TABLE `juego`
  ADD PRIMARY KEY (`id_juego`);

--
-- Indices de la tabla `root`
--
ALTER TABLE `root`
  ADD PRIMARY KEY (`id_root`);

--
-- Indices de la tabla `root_tel`
--
ALTER TABLE `root_tel`
  ADD PRIMARY KEY (`tel`,`id_root`),
  ADD KEY `id_root` (`id_root`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`nom_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administra`
--
ALTER TABLE `administra`
  MODIFY `id_grupo` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habla`
--
ALTER TABLE `habla`
  MODIFY `id_juego` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juega`
--
ALTER TABLE `juega`
  MODIFY `id_juego` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `root`
--
ALTER TABLE `root`
  MODIFY `id_root` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `root_tel`
--
ALTER TABLE `root_tel`
  MODIFY `id_root` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administra`
--
ALTER TABLE `administra`
  ADD CONSTRAINT `administra_ibfk_1` FOREIGN KEY (`nom_usuario`) REFERENCES `usuarios` (`nom_usuario`),
  ADD CONSTRAINT `administra_ibfk_2` FOREIGN KEY (`id_grupo`) REFERENCES `grupo` (`id_grupo`);

--
-- Filtros para la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD CONSTRAINT `grupo_ibfk_1` FOREIGN KEY (`nom_usuario`) REFERENCES `usuarios` (`nom_usuario`);

--
-- Filtros para la tabla `habla`
--
ALTER TABLE `habla`
  ADD CONSTRAINT `habla_ibfk_1` FOREIGN KEY (`nom_usuario_emisor`) REFERENCES `usuarios` (`nom_usuario`),
  ADD CONSTRAINT `habla_ibfk_2` FOREIGN KEY (`nom_usuario_receptor`) REFERENCES `usuarios` (`nom_usuario`),
  ADD CONSTRAINT `habla_ibfk_3` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`);

--
-- Filtros para la tabla `juega`
--
ALTER TABLE `juega`
  ADD CONSTRAINT `juega_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`),
  ADD CONSTRAINT `juega_ibfk_2` FOREIGN KEY (`nom_usuario`) REFERENCES `usuarios` (`nom_usuario`);

--
-- Filtros para la tabla `root_tel`
--
ALTER TABLE `root_tel`
  ADD CONSTRAINT `root_tel_ibfk_1` FOREIGN KEY (`id_root`) REFERENCES `root` (`id_root`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
