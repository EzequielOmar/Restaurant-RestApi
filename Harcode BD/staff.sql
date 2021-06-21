-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-06-2021 a las 21:30:15
-- Versión del servidor: 8.0.13-4
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.7

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
(1, '99999999', 'tito', 'Levando', 'tito', 1, 3, '2020-07-27 00:00:00', NULL),
(2, '461993353', 'Ike', 'Bristoe', 'oHpv6ltnGh3p', 2, 3, '2020-12-29 00:00:00', NULL),
(3, '185184348', 'Josepha', 'Coonan', 'rUHvXEc4ST', 2, 3, '2020-11-23 00:00:00', NULL),
(4, '124304382', 'Leonora', 'Joutapaitis', 'I9QBGTxYS1', 2, 3, '2021-05-11 00:00:00', NULL),
(5, '332641854', 'Ancell', 'Horning', 'jtlgdvBvvVk', 3, 3, '2020-07-05 00:00:00', NULL),
(6, '353906898', 'Madelena', 'McAlinion', 'EZr1Ly', 3, 3, '2021-02-28 00:00:00', NULL),
(7, '53217199', 'Courtnay', 'Ruckledge', 'yKVYJqwz9BP', 4, 3, '2021-06-04 00:00:00', NULL),
(8, '219831213', 'Stavro', 'Templman', 'QqQPiNpPoEzd', 4, 3, '2020-11-15 00:00:00', NULL),
(9, '256104761', 'Diane-marie', 'Cashford', 'PDKdoAG', 5, 3, '2021-02-23 00:00:00', NULL),
(10, '323061809', 'Fina', 'Weavill', 'htrUet0SjwD', 5, 3, '2020-08-02 00:00:00', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
