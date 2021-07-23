-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-07-2021 a las 16:23:26
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
-- Estructura de tabla para la tabla `comentario`
--

CREATE TABLE `comentario` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `rate_mesa` int(2) NOT NULL,
  `rate_rest` int(2) NOT NULL,
  `rate_mozo` int(2) NOT NULL,
  `rate_cocina` int(2) NOT NULL,
  `comentario` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `comentario`
--

INSERT INTO `comentario` (`id`, `id_mesa`, `rate_mesa`, `rate_rest`, `rate_mozo`, `rate_cocina`, `comentario`, `fecha`) VALUES
(1, 4, 1, 7, 9, 2, 'Gran servicio.', '2021-01-10 02:35:00'),
(2, 15, 4, 3, 3, 2, NULL, '2021-10-14 01:00:00'),
(3, 13, 7, 6, 1, 1, 'Pesima actitud del mozo, lo demas muy bien.', '2021-09-30 03:25:00'),
(4, 10, 3, 6, 10, 8, 'Lindo lugar.', '2021-11-25 01:25:00'),
(5, 8, 6, 9, 4, 5, 'El resto se lleva el premio.', '2021-02-26 03:05:00'),
(6, 1, 1, 10, 1, 6, NULL, '2021-09-17 00:05:22'),
(7, 2, 1, 5, 1, 4, 'Se tardaron demasiado en traer el pedido, la mesa estaba sucia.', '2021-08-12 14:25:00'),
(8, 4, 9, 9, 5, 7, NULL, '2021-03-15 08:14:00'),
(9, 3, 10, 6, 10, 4, NULL, '2021-04-02 00:00:15'),
(10, 6, 8, 8, 3, 10, 'El mozo era feo, y se le caen las cosas.', '2021-07-04 10:15:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentario`
--
ALTER TABLE `comentario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
