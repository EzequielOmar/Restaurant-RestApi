-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-07-2021 a las 22:22:35
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
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `operacion` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `id_staff` int(11) NOT NULL,
  `sector` int(1) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id`, `operacion`, `id_staff`, `sector`, `fecha`) VALUES
(1, 'Login', 5, 4, '2021-07-01 05:38:23'),
(2, 'Login', 5, 4, '2021-07-01 03:10:23'),
(3, 'Toma servicio', 5, 4, '2021-07-01 14:36:46'),
(4, 'Login', 3, 2, '2021-07-01 10:24:17'),
(5, 'Toma mesa', 3, 2, '2021-07-03 07:51:26'),
(6, 'Logout', 7, 2, '2021-07-03 11:05:30'),
(7, 'Despacho', 6, 5, '2021-07-07 06:40:46'),
(8, 'Login', 6, 5, '2021-07-10 03:25:27'),
(9, 'Despacho', 6, 5, '2021-07-10 17:35:37'),
(10, 'Login', 5, 4, '2021-07-14 05:22:34'),
(11, 'Logout', 6, 5, '2021-07-15 05:29:36'),
(12, 'Toma servicio', 5, 4, '2021-07-15 00:00:00'),
(13, 'Despacho', 8, 4, '2021-07-20 05:16:00'),
(14, 'Toma servicio', 5, 4, '2021-07-20 00:31:40'),
(15, 'Logout', 6, 5, '2021-07-22 00:39:00'),
(16, 'Despacho', 5, 4, '2021-07-23 15:39:00'),
(17, 'Login', 4, 3, '2021-07-24 18:25:10'),
(18, 'Login', 5, 4, '2021-07-24 18:48:17');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
