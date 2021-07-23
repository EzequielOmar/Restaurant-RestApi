-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-07-2021 a las 16:12:31
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
(1, 7, 'oh4U1', 5, '$3777.35', '2021-03-07 17:58:51'),
(2, 12, 'jPVPV', 5, '$2105.32', '2021-06-21 12:34:12'),
(3, 6, 'gDNbE', 7, '$772', '2021-05-11 11:25:11'),
(4, 10, 'D3wc7', 8, '$307.36', '2021-05-03 18:33:12'),
(5, 1, 'uK3uL', 1, '$614.72', '2021-05-02 21:53:55'),
(6, 7, 'rpRuM', 9, '$1763.02', '2021-04-10 23:47:15'),
(7, 3, '495mg', 6, '$3673.95', '2021-03-02 03:18:35'),
(8, 1, 'D9DD5', 5, '$526.33', '2021-02-20 16:37:16'),
(9, 7, 'Rr0sN', 7, '$1854.68', '2021-02-04 13:54:21');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
