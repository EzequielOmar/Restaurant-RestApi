-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-07-2021 a las 23:50:42
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
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `codigo_mesa` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `estado` int(1) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_mozo` int(11) NOT NULL,
  `id_elaborador` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora_comandado` time NOT NULL,
  `hora_tomado` time DEFAULT NULL,
  `hora_estimada` time DEFAULT NULL,
  `hora_listo` time DEFAULT NULL,
  `hora_entregado` time DEFAULT NULL,
  `hora_cierre` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `codigo`, `codigo_mesa`, `estado`, `id_producto`, `cantidad`, `id_cliente`, `id_mozo`, `id_elaborador`, `fecha`, `hora_comandado`, `hora_tomado`, `hora_estimada`, `hora_listo`, `hora_entregado`, `hora_cierre`) VALUES
(1, 'VkNga', '07eGq', 5, 13, 2, 3, 2, 0, '2021-01-29', '04:00:51', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '04:45:51'),
(2, 'x96G9', 'd59Hm', 5, 9, 3, 7, 3, 4, '2021-01-31', '05:57:20', '05:59:20', '20:00:00', '00:00:00', '00:00:00', '06:37:10'),
(3, 'Fwe4f', 'su3WY', 5, 6, 2, 8, 3, 0, '2021-02-02', '01:41:56', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '01:43:36'),
(4, 'a9Pj5', 'd59Hm', 5, 8, 1, 7, 3, 5, '2021-02-03', '21:49:48', '22:15:48', '10:00:00', '21:27:48', '00:00:00', '21:29:18'),
(5, 'Rr0sN', 'qWoun', 4, 2, 5, 7, 3, 5, '2021-02-04', '00:33:09', '00:35:09', '15:00:00', '00:47:29', '00:50:09', '01:12:32'),
(6, 'TkCRN', '5r65L', 5, 6, 1, 8, 2, 0, '2021-02-10', '07:48:59', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '08:08:09'),
(7, 'wNXEK', 'SJGKK', 5, 4, 3, 10, 3, 4, '2021-02-19', '00:03:55', '00:05:05', '10:00:00', '00:00:00', '00:00:00', '00:35:51'),
(8, 'D9DD5', 'OL8DN', 4, 3, 1, 5, 3, 4, '2021-02-20', '15:47:16', '15:50:16', '15:00:00', '16:12:16', '16:14:26', '16:38:16'),
(9, '55g0s', 'K5qME', 5, 13, 1, 1, 3, 5, '2021-03-01', '13:19:31', '13:21:31', '20:00:00', '13:38:11', '00:00:00', '13:55:21'),
(10, '495mg', 'su3WY', 4, 4, 5, 6, 2, 4, '2021-03-02', '02:29:35', '02:31:35', '15:00:00', '02:50:35', '02:51:35', '03:19:35'),
(11, 'oh4U1', 'qWoun', 4, 15, 5, 5, 2, 6, '2021-03-07', '17:31:51', '17:33:51', '17:00:00', '17:45:51', '17:46:51', '17:59:51'),
(12, 'WgHvi', 'uwDyg', 5, 13, 4, 5, 3, 5, '2021-03-16', '21:47:55', '22:05:55', '20:00:00', '22:24:55', '00:00:00', '22:25:55'),
(13, 'eBFKA', 'SZPQs', 5, 1, 2, 9, 3, 0, '2021-04-17', '23:43:26', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '23:59:26'),
(14, 'rpRuM', '272VZ', 4, 6, 2, 9, 2, 5, '2021-04-10', '23:09:15', '23:12:15', '15:00:00', '23:24:15', '23:26:15', '23:48:15'),
(15, 'uK3uL', 'OL8DN', 4, 14, 2, 1, 3, 6, '2021-05-02', '21:03:55', '21:05:55', '20:00:00', '21:20:55', '21:23:55', '21:54:55'),
(16, 'D3wc7', 'sYHHi', 4, 14, 1, 8, 3, 6, '2021-05-03', '17:54:12', '17:59:12', '15:00:00', '00:00:00', '00:00:00', '18:34:12'),
(17, 'gDNbE', 'V3mU1', 4, 7, 4, 7, 3, 5, '2021-05-11', '10:34:11', '10:36:11', '15:00:00', '10:48:11', '10:50:11', '11:26:11'),
(18, '1Bwou', '272VZ', 5, 5, 3, 8, 3, 0, '2021-05-14', '17:19:48', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '17:56:00'),
(19, 'WNSpM', 'RUzRd', 5, 15, 1, 5, 2, 6, '2021-06-14', '15:15:52', '15:18:52', '15:00:00', '15:32:52', '00:00:00', '15:55:52'),
(20, 'jPVPV', 'QCv2K', 4, 3, 4, 5, 2, 4, '2021-06-21', '11:53:12', '11:57:12', '15:00:00', '12:13:12', '00:00:00', '12:33:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
