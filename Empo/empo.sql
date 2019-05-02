-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 02-05-2019 a las 13:15:04
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `empo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id_alumno` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id_alumno`, `id_usuario`, `id_curso`) VALUES
(1, 2, 1),
(8, 9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignatura`
--

CREATE TABLE `asignatura` (
  `id_asignatura` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `asignatura`
--

INSERT INTO `asignatura` (`id_asignatura`, `nombre`, `id_curso`) VALUES
(1, 'Programación', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro`
--

CREATE TABLE `centro` (
  `id_centro` int(11) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `biografia` text,
  `descripcion` text,
  `imagen_personal` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `id_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `centro`
--

INSERT INTO `centro` (`id_centro`, `nif`, `nombre`, `password`, `biografia`, `descripcion`, `imagen_personal`, `email`, `id_token`) VALUES
(1, '12345678M', 'Stucom', '1234', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id_curso`, `nombre`, `id_centro`) VALUES
(1, 'DAW', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `cuerpo` text NOT NULL,
  `fecha` date NOT NULL,
  `cerrado` tinyint(1) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `post`
--

INSERT INTO `post` (`id_post`, `titulo`, `cuerpo`, `fecha`, `cerrado`, `id_alumno`, `id_asignatura`) VALUES
(4, 'PHP', 'JuanPablo', '2019-04-03', 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_vs_tema`
--

CREATE TABLE `post_vs_tema` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_tema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `id_profesor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_vs_asignatura`
--

CREATE TABLE `profesor_vs_asignatura` (
  `id` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `id_asignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id_respuesta` int(11) NOT NULL,
  `asunto` varchar(50) NOT NULL,
  `texto` text NOT NULL,
  `fecha` date NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_respuesta_padre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `respuesta`
--

INSERT INTO `respuesta` (`id_respuesta`, `asunto`, `texto`, `fecha`, `id_post`, `id_usuario`, `id_respuesta_padre`) VALUES
(2, 'PHP', 'Soy mejor', '2019-04-30', 4, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_guardada`
--

CREATE TABLE `respuesta_guardada` (
  `id_guardado` int(11) NOT NULL,
  `id_respuesta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_reportada`
--

CREATE TABLE `respuesta_reportada` (
  `id_reporte` int(11) NOT NULL,
  `id_respuesta` int(11) NOT NULL,
  `tipo_reporte` varchar(50) NOT NULL,
  `descripcion` text,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tema`
--

CREATE TABLE `tema` (
  `id_tema` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `imagen_personal` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `biografia` text,
  `id_centro` int(11) NOT NULL,
  `id_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nif`, `nombre`, `password`, `tipo`, `imagen_personal`, `email`, `biografia`, `id_centro`, `id_token`) VALUES
(2, '49645331S!', 'oscar', '1234', 'Alumno', NULL, 'oscarcj98@gmail.com', NULL, 1, '6a80fec1f2fbcfc17a7470daea1944e3c8eb10b5cb6d19ad3bb23ecf3b6a598fc5bfad8cfcf8c0b5685d9ba2b2891c830b50'),
(9, '321543678M', 'Revenge', '$2y$10$6ggVbqmQ4TGnN7mxZ5FnkObSpCig2Ra0nTDkAJViXOZLTnYZCaGdG', 'Alumno', NULL, 'hash3@gmail.com', NULL, 1, '6c6885e9f9041bf0fe4281eb9849db38058fe22d38798a54ae5f6080c246b596e3651f5b91674d3564c28ce7fbf5fe695953');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_alumno_curso` (`id_curso`);

--
-- Indices de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD PRIMARY KEY (`id_asignatura`),
  ADD KEY `fk_asignatura_curso` (`id_curso`);

--
-- Indices de la tabla `centro`
--
ALTER TABLE `centro`
  ADD PRIMARY KEY (`id_centro`),
  ADD UNIQUE KEY `nif` (`nif`);

--
-- Indices de la tabla `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`),
  ADD KEY `fk_curso_centro` (`id_centro`);

--
-- Indices de la tabla `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `fk_post_alumno` (`id_alumno`),
  ADD KEY `fk_post_asignatura` (`id_asignatura`);

--
-- Indices de la tabla `post_vs_tema`
--
ALTER TABLE `post_vs_tema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_vs_tema_post` (`id_post`),
  ADD KEY `fk_post_vs_tema_tema` (`id_tema`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id_profesor`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `profesor_vs_asignatura`
--
ALTER TABLE `profesor_vs_asignatura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profesor_vs_asignatura_profesor` (`id_profesor`),
  ADD KEY `fk_profesor_vs_asignatura_asignatura` (`id_asignatura`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `fk_respuesta_post` (`id_post`),
  ADD KEY `fk_respuesta_usuario` (`id_usuario`),
  ADD KEY `fk_respuesta_padre` (`id_respuesta_padre`);

--
-- Indices de la tabla `respuesta_guardada`
--
ALTER TABLE `respuesta_guardada`
  ADD PRIMARY KEY (`id_guardado`),
  ADD KEY `fk_respuesta_guardada_respuesta` (`id_respuesta`),
  ADD KEY `fk_respuesta_guardada_usuario` (`id_usuario`);

--
-- Indices de la tabla `respuesta_reportada`
--
ALTER TABLE `respuesta_reportada`
  ADD PRIMARY KEY (`id_reporte`),
  ADD KEY `fk_respuesta_reportada_respuesta` (`id_respuesta`),
  ADD KEY `fk_respuesta_reportada_usuario` (`id_usuario`);

--
-- Indices de la tabla `tema`
--
ALTER TABLE `tema`
  ADD PRIMARY KEY (`id_tema`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nif` (`nif`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuario_centro` (`id_centro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `asignatura`
--
ALTER TABLE `asignatura`
  MODIFY `id_asignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `centro`
--
ALTER TABLE `centro`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `post_vs_tema`
--
ALTER TABLE `post_vs_tema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesor_vs_asignatura`
--
ALTER TABLE `profesor_vs_asignatura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `respuesta_guardada`
--
ALTER TABLE `respuesta_guardada`
  MODIFY `id_guardado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuesta_reportada`
--
ALTER TABLE `respuesta_reportada`
  MODIFY `id_reporte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tema`
--
ALTER TABLE `tema`
  MODIFY `id_tema` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD CONSTRAINT `fk_alumno_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_alumno_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asignatura`
--
ALTER TABLE `asignatura`
  ADD CONSTRAINT `fk_asignatura_curso` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`);

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `fk_curso_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`);

--
-- Filtros para la tabla `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_alumno` FOREIGN KEY (`id_alumno`) REFERENCES `alumno` (`id_alumno`),
  ADD CONSTRAINT `fk_post_asignatura` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`) ON DELETE CASCADE;

--
-- Filtros para la tabla `post_vs_tema`
--
ALTER TABLE `post_vs_tema`
  ADD CONSTRAINT `fk_post_vs_tema_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_vs_tema_tema` FOREIGN KEY (`id_tema`) REFERENCES `tema` (`id_tema`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `fk_profesor_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesor_vs_asignatura`
--
ALTER TABLE `profesor_vs_asignatura`
  ADD CONSTRAINT `fk_profesor_vs_asignatura_asignatura` FOREIGN KEY (`id_asignatura`) REFERENCES `asignatura` (`id_asignatura`),
  ADD CONSTRAINT `fk_profesor_vs_asignatura_profesor` FOREIGN KEY (`id_profesor`) REFERENCES `profesor` (`id_profesor`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `fk_respuesta_padre` FOREIGN KEY (`id_respuesta_padre`) REFERENCES `respuesta` (`id_respuesta`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_respuesta_post` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_respuesta_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `respuesta_guardada`
--
ALTER TABLE `respuesta_guardada`
  ADD CONSTRAINT `fk_respuesta_guardada_respuesta` FOREIGN KEY (`id_respuesta`) REFERENCES `respuesta` (`id_respuesta`),
  ADD CONSTRAINT `fk_respuesta_guardada_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `respuesta_reportada`
--
ALTER TABLE `respuesta_reportada`
  ADD CONSTRAINT `fk_respuesta_reportada_respuesta` FOREIGN KEY (`id_respuesta`) REFERENCES `respuesta` (`id_respuesta`),
  ADD CONSTRAINT `fk_respuesta_reportada_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
