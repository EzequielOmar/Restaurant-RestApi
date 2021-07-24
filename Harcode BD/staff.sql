-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-07-2021 a las 18:27:14
-- Versión del servidor: 8.0.13-4
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `AJs1FUV3kA`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `dni` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sector` int(1) NOT NULL,
  `estado` int(1) NOT NULL,
  `fecha_ing` datetime NOT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `staff`
--

INSERT INTO `staff` (`id`, `dni`, `nombre`, `apellido`, `clave`, `sector`, `estado`, `fecha_ing`, `fecha_baja`) VALUES
(1, '99999999', 'tito', 'Levando', '1a96f9437697ef43237868412d77b15991964f6e', 1, 3, '2020-07-27 00:00:00', NULL),
(2, '39123123', 'Gimena', 'Lopez', '484d134c26b764c6c420687039f63553bb153b0c', 2, 3, '2021-06-26 20:45:32', '2021-06-28 20:45:32'),
(3, '40000000', 'Tomatito', 'Pena', '88558fe7c495fde75d96bc153460c3c6f5323d0f', 2, 3, '2021-06-26 20:47:53', NULL),
(4, '40000001', 'Papu', 'Gomez', '8d656736816630486ca3c40feffdc0c96f03de1b', 3, 1, '2021-06-26 20:48:39', NULL),
(5, '40000002', 'Megan', 'Rapinoe', '77b0714f18e4a74e0ba14d0067cb1734f26437f8', 4, 3, '2021-06-26 20:49:25', NULL),
(6, '40000003', 'Edwing', 'Caradona', '3b4ab6032b6899388553f4ee507c570ee3fdeef8', 5, 1, '2021-06-26 20:50:29', NULL),
(7, '40000004', 'Angela', 'Lerena', 'e1abbe2df5387f475e1aaa4ebf1dafe16d142755', 2, 3, '2021-06-26 20:59:46', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
