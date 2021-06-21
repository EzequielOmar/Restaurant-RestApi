-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-06-2021 a las 22:17:25
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
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `codigo_pedido` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `monto` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `fecha` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id`, `id_mesa`, `codigo_pedido`, `id_cliente`, `monto`, `fecha`) VALUES
(10, 10, 'rohiZ', 8, '$8367.20', '2020-09-15 00:00:00'),
(11, 11, 'AsFrI', 9, '$7223.37', '2021-01-22 00:00:00'),
(12, 8, '7ZOrA', 4, '$60.78', '2021-04-11 00:00:00'),
(13, 6, 'TQ5zr', 2, '$4759.15', '2020-12-24 00:00:00'),
(14, 2, 'U9Z8W', 6, '$1114.02', '2020-10-31 00:00:00'),
(15, 12, 'lamXe', 8, '$1331.35', '2020-12-27 00:00:00'),
(16, 7, 'fRGpk', 3, '$3134.53', '2021-01-22 00:00:00'),
(17, 9, 'MgUWx', 4, '$8878.11', '2021-03-06 00:00:00'),
(18, 7, 'osoxK', 3, '$8219.94', '2021-05-28 00:00:00'),
(19, 8, 'M0QZg', 9, '$1006.24', '2021-04-13 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
