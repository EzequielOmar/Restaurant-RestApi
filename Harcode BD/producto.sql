-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-06-2021 a las 22:05:09
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
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sector` int(1) NOT NULL,
  `precio` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `descripcion`, `sector`, `precio`, `stock`) VALUES
(1, 'American Ipa', 'Rubia amarga intensa molesta.', 5, '$140.65', 28),
(2, 'Bouquet Garnie', 'No quere saber...', 4, '$927.34', 7),
(3, 'Martini Seco', 'Con aceituna y todi', 3, '$526.33', 74),
(4, 'Kentocky on the rocks', 'Inventadisimo', 3, '$734.79', 23),
(5, 'Amber Pale Ale', 'Rojiza cabrona', 5, '$738.84', 24),
(6, 'Salchicha a la Pomarola', 'Mas Salchicha que pomarola.', 4, '$881.51', 87),
(7, 'Rizzoto al curry', 'Tarda 55 minutos minimo.', 4, '$193.00', 4),
(8, 'Cabeza de ajo al vino blanco.', 'Para tener la boca cerrada un buen rato.', 4, '$503.42', 33),
(9, 'Cynar con speed', 'Sale con fritanga', 3, '$25.80', 56),
(10, 'Porter', 'A cafe mas que chicha', 5, '$762.56', 90),
(11, 'Ipa doble Ipa doble', 'Ya no se', 5, '$31.23', 74),
(12, 'Fideo con tuco', 'De la casa (de josé)', 4, '$674.17', 15),
(13, 'Ravioles con tinta de escribir', 'Azul', 4, '$122.51', 65),
(14, 'Miller', 'Rubia tristona comercial.', 5, '$307.36', 80),
(15, 'Stout Ipa', 'Potente como patada de burdisso.', 5, '$755.47', 58);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
