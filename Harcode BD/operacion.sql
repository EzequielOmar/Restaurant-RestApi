-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-07-2021 a las 18:36:19
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
-- Estructura de tabla para la tabla `operacion`
--

CREATE TABLE `operacion` (
  `id` int(11) NOT NULL,
  `operacion` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `id_staff` int(11) NOT NULL,
  `sector` int(11) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `operacion`
--

INSERT INTO `operacion` (`id`, `operacion`, `id_staff`, `sector`, `fecha`) VALUES
(1, 'Login', 5, 4, '2021-07-01 00:00:00'),
(2, 'Login', 5, 4, '2021-07-01 00:00:00'),
(3, 'Toma servicio', 5, 4, '2021-07-01 00:00:00'),
(4, 'Login', 3, 2, '2021-07-01 00:00:00'),
(5, 'Toma servicio', 3, 2, '2021-07-03 00:00:00'),
(6, 'Logout', 7, 2, '2021-07-03 00:00:00'),
(7, 'Despacho', 6, 5, '2021-07-07 00:00:00'),
(8, 'Login', 6, 5, '2021-07-10 00:00:00'),
(9, 'Despacho', 6, 5, '2021-07-10 00:00:00'),
(10, 'Login', 5, 4, '2021-07-14 00:00:00'),
(11, 'Logout', 6, 5, '2021-07-15 00:00:00'),
(12, 'Toma servicio', 5, 4, '2021-07-15 00:00:00'),
(13, 'Despacho', 5, 4, '2021-07-20 00:00:00'),
(14, 'Toma servicio', 5, 4, '2021-07-20 00:00:00'),
(15, 'Logout', 6, 5, '2021-07-22 00:00:00'),
(16, 'Despacho', 5, 4, '2021-07-23 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `operacion`
--
ALTER TABLE `operacion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `operacion`
--
ALTER TABLE `operacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
