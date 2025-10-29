-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 29-10-2025 a las 19:26:14
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
-- Base de datos: `halloween`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disfraces`
--

CREATE TABLE `disfraces` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `votos` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `foto_blob` blob NOT NULL,
  `eliminado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disfraces`
--

INSERT INTO `disfraces` (`id`, `nombre`, `descripcion`, `votos`, `foto`, `foto_blob`, `eliminado`) VALUES
(0, 'payaso', 'payaso terrorifico ', 5, '1761709145_payaso.jp', '', 1),
(0, 'ghost face', 'ghost face de la película scream', 5, '1761709839_scream.jp', '', 1),
(0, 'pirata', 'pirata', 3, '1761752674_pirata.jp', '', 1),
(0, 'ghost face', 'ghost face de la pelicula scream', 1, '1761752793_scream.jp', '', 1),
(0, 'pirata', 'pirata 2', 0, '1761753364_pirata.jpeg', '', 1),
(0, 'pirata', 'mujer pirata ', 0, '1761753446_pirata.jpeg', '', 0),
(0, 'ghost face', 'ghost face de la pelicula scream', 0, '1761753473_scream.jpg', '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `clave` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`) VALUES
(1, 'emanuel', '$2y$10$ruO1iWBOtvw2XJCh4lZtreYOyVTejr4fHrpwHN6SGsrheNZap5/.y'),
(2, 'juan', '$2y$10$RCoFO8qoUJQEpMKaVhpAA.zpnxAOHc7BCALj8qPRcSVCSx9IQuQRi'),
(3, 'carla', '$2y$10$CTRyJ5izECaII5oTSTOR6O7P4HvjajXCxlRj0rqxft.In1S.b3P4q'),
(4, 'lol', '$2y$10$HCxxfKEL/AGtTSpHpfh/DukWl94xg0VHCioS8zYnYBxr5gUyxUcQ.'),
(5, '123', '$2y$10$D9KWpbTnqDlYDMnZVkvGvu6r9bN/CCYv7g0awjW7kFpgNWhsque2q'),
(6, '5555', '$2y$10$wOfHnbIMoCQ./VJYaMNpfO0911sdAxbLk9AT0qYGwgzXFtAk1jh26'),
(7, 'juana', '$2y$10$5IQnq1pB72PCmbO0q0KyAeSV1zpzreRGQhcJ4POg4ajmopQEwds4.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

CREATE TABLE `votos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_disfraz` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `votos`
--

INSERT INTO `votos` (`id`, `id_usuario`, `id_disfraz`) VALUES
(0, 1, 0),
(0, 3, 0),
(0, 4, 0),
(0, 2, 0),
(0, 7, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
