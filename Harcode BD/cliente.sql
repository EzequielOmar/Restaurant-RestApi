-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-06-2021 a las 21:31:18
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
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `mail` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cel` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_ing` datetime NOT NULL,
  `fecha_modif` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `mail`, `nombre`, `apellido`, `clave`, `cel`, `fecha_ing`, `fecha_modif`, `fecha_baja`) VALUES
(1, 'aguilbert0@odnoklassniki.ru', 'Andros', 'Guilbert', '1SdEyyXyfXxo', '2569602261', '2020-11-16 00:00:00', NULL, NULL),
(2, 'cgabbett1@dmoz.org', 'Corrianne', 'Gabbett', 'xk5engCW16oQ', '7595539719', '2020-08-24 00:00:00', NULL, NULL),
(3, 'ekuhle2@diigo.com', 'Ellswerth', 'Kuhle', 'S30uvKv', '2259035837', '2020-09-10 00:00:00', NULL, NULL),
(4, 'adiaper3@lulu.com', 'Andromache', 'Diaper', 'YaLCfP', '9482792763', '2020-12-14 00:00:00', NULL, NULL),
(5, 'crubke4@ucoz.ru', 'Casper', 'Rubke', 'hvvyoTu', '8129954810', '2020-11-16 00:00:00', NULL, NULL),
(6, 'swilley5@tinypic.com', 'Selle', 'Willey', 'FlREZgzY', '2028772464', '2020-11-24 00:00:00', NULL, NULL),
(7, 'rhick6@amazon.co.uk', 'Raimondo', 'Hick', 'bnzd32WV', '8336016746', '2021-02-06 00:00:00', NULL, NULL),
(8, 'ktuxell7@about.me', 'Kendra', 'Tuxell', 'RRutoAylLv3k', '9774665891', '2021-01-30 00:00:00', NULL, NULL),
(9, 'ptresler8@people.com.cn', 'Panchito', 'Tresler', '6LlLOUgvrOt', '5145344500', '2021-02-25 00:00:00', NULL, NULL),
(10, 'cpaolini9@tmall.com', 'Clare', 'Paolini', 'scQB4WvHTZ', '3723957466', '2021-03-12 00:00:00', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
