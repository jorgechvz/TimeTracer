-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-01-2024 a las 04:56:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sensordata`
--

CREATE TABLE `sensordata` (
  `id` int(11) NOT NULL,
  `temperature` double NOT NULL,
  `humidity` double NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sensordata`
--

INSERT INTO `sensordata` (`id`, `temperature`, `humidity`, `datetime`) VALUES
(387, 25.6, 95, '2024-01-11 04:34:53'),
(388, 25.7, 95, '2024-01-11 04:35:07'),
(389, 25.7, 95, '2024-01-11 04:35:22'),
(390, 25.8, 95, '2024-01-11 04:35:32'),
(391, 26, 95, '2024-01-11 04:35:42'),
(392, 26.1, 95, '2024-01-11 04:35:53'),
(393, 26.3, 95, '2024-01-11 04:36:03'),
(394, 26.3, 95, '2024-01-11 04:36:13'),
(395, 26.5, 95, '2024-01-11 04:36:23'),
(396, 26.6, 95, '2024-01-11 04:36:38'),
(397, 26.8, 95, '2024-01-11 04:36:48'),
(398, 26.9, 95, '2024-01-11 04:36:58'),
(399, 27, 95, '2024-01-11 04:37:08'),
(400, 27, 95, '2024-01-11 04:37:18'),
(401, 27.1, 95, '2024-01-11 04:37:28'),
(402, 27.1, 95, '2024-01-11 04:37:38'),
(403, 27, 95, '2024-01-11 04:37:49'),
(404, 27.1, 95, '2024-01-11 04:37:59'),
(405, 27.1, 95, '2024-01-11 04:38:09'),
(406, 27.1, 95, '2024-01-11 04:38:19'),
(407, 27.1, 95, '2024-01-11 04:38:29'),
(408, 27.2, 95, '2024-01-11 04:38:39'),
(409, 27.2, 95, '2024-01-11 04:38:49');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sensordata`
--
ALTER TABLE `sensordata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sensordata`
--
ALTER TABLE `sensordata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=410;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
