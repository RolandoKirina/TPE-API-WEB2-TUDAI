-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2022 a las 19:52:51
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tpe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item`
--

CREATE TABLE `item` (
  `id_chocolate` int(11) NOT NULL,
  `nombre_chocolate` varchar(45) NOT NULL,
  `precio_unidad` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `stock` int(11) NOT NULL,
  `img` varchar(50) DEFAULT NULL,
  `id_marca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `item`
--

INSERT INTO `item` (`id_chocolate`, `nombre_chocolate`, `precio_unidad`, `descripcion`, `stock`, `img`, `id_marca`) VALUES
(67, 'Dos Corazones', 250, 'Chocolate felfort', 123, 'imgs63630e5eb4c96.png', 7),
(68, 'Milka', 250, 'Chocolate negro', 2, NULL, 55),
(70, 'Ferrero Rocher', 500, 'Chocolate negro', 5, NULL, 58),
(73, 'Rocklets', 120, 'Chocolate negro', 10, NULL, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(45) NOT NULL,
  `anio_creacion` int(4) NOT NULL,
  `pais_marca` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`, `anio_creacion`, `pais_marca`) VALUES
(3, 'hola', 1946, 'Italia'),
(7, 'Felfort', 1912, 'Argentina'),
(55, 'Milka', 1901, 'Suiza'),
(57, 'Ferrero', 1946, 'Italia'),
(58, 'Ferrero', 1946, 'Italia'),
(60, 'Arcor', 1951, 'Argentina');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review`
--

CREATE TABLE `review` (
  `id_review` int(11) NOT NULL,
  `review` text NOT NULL,
  `score` int(15) NOT NULL,
  `fk_id_chocolate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `review`
--

INSERT INTO `review` (`id_review`, `review`, `score`, `fk_id_chocolate`) VALUES
(1, 'Muy buen chocolate mi favorito :)', 5, 67),
(2, 'No me gusto ', 1, 68),
(3, 'Muy buen chocolate mi favorito :)', 5, 67),
(4, 'No me gusto ', 1, 68),
(5, 'Muy dulce para mi gusto...', 3, 70),
(6, 'Muy dulce para mi gusto...', 3, 70),
(7, 'Me gusto', 4, 68),
(8, 'Me gusto', 4, 68),
(9, 'Muy caro', 2, 70),
(10, 'Muy caro', 2, 70),
(11, 'Genial ', 5, 73),
(12, 'Genial ', 5, 73),
(13, 'Horrible', 1, 73),
(14, 'Horrible', 1, 73);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(3, 'prueba@gmail.com', '$2a$12$MMYXog7nr3fOt92TGAmL/eaPKKElgzAgMjN6pa8z8h9xzHb.62Fr2');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_chocolate`),
  ADD KEY `FK_id_marca` (`id_marca`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `FK_id_item` (`fk_id_chocolate`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `item`
--
ALTER TABLE `item`
  MODIFY `id_chocolate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`);

--
-- Filtros para la tabla `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`fk_id_chocolate`) REFERENCES `item` (`id_chocolate`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
