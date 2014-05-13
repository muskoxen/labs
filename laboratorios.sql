-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2014 a las 02:52:08
-- Versión del servidor: 5.5.32
-- Versión de PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `laboratorios`
--
CREATE DATABASE IF NOT EXISTS `laboratorios` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `laboratorios`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE IF NOT EXISTS `estados` (
  `estadoId` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`estadoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`estadoId`, `estado`) VALUES
(1, 'Sinaloa'),
(2, 'Sonora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE IF NOT EXISTS `modulos` (
  `id_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `clase` varchar(45) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `modulo`, `clase`) VALUES
(1, 'Modulos', 'Modulo'),
(2, 'Procesos', 'Proceso'),
(3, 'Roles', 'Rol'),
(4, 'Usuarios', 'Usuario'),
(5, 'Panel', 'Panel'),
(6, 'Pacientes', 'Pacientes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE IF NOT EXISTS `municipios` (
  `municipioId` int(11) NOT NULL AUTO_INCREMENT,
  `estadoId` int(11) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`municipioId`),
  KEY `estadoId_idx` (`estadoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`municipioId`, `estadoId`, `municipio`) VALUES
(1, 1, 'Culiacán'),
(2, 1, 'Navolato'),
(3, 2, 'Cd. Obregón'),
(4, 2, 'Hermosillo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE IF NOT EXISTS `pacientes` (
  `pacienteId` int(11) NOT NULL AUTO_INCREMENT,
  `municipioId` int(11) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellidop` varchar(20) DEFAULT NULL,
  `apellidom` varchar(20) DEFAULT NULL,
  `colonia` varchar(150) DEFAULT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `numero` varchar(15) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `sexo` enum('hombre','mujer') DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `codigoPostal` varchar(10) DEFAULT NULL,
  `medios` enum('internet','tv','radio','folletos','otros') DEFAULT NULL,
  `otroMedio` varchar(150) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT NULL,
  PRIMARY KEY (`pacienteId`),
  KEY `municipioId_idx` (`municipioId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`pacienteId`, `municipioId`, `nombre`, `apellidop`, `apellidom`, `colonia`, `calle`, `numero`, `telefono`, `email`, `sexo`, `fechaNacimiento`, `codigoPostal`, `medios`, `otroMedio`, `fechaRegistro`) VALUES
(1, 1, 'Luis', 'Benitez', 'Padilla', 'Rosales', 'Jaramillo', '90', '6671010101', '0', 'hombre', '2014-05-17', '10101', '', '', '2014-05-12 07:46:34'),
(2, 2, 'Cesar', 'Monzon', '', 'Conocido', 'N', '80', '5551999999', 'cesar@luxerv.com', 'hombre', '2010-02-01', '8000', '', '', '2014-05-12 18:25:46'),
(3, 1, 'Angela', 'Paredes', 'Ayala', 'Coloradas', 'Iturbide', '7', '8990090901', 'angela@paredes.com', 'mujer', '1985-05-01', '1', '', '', '2014-05-12 20:20:32'),
(4, 4, 'Maria Fernanda', 'Rodriguez', 'Esparza', 'N', 'N', 'N', '8990010101', 'mariafernanda@gmail.com', 'mujer', '1990-03-01', '90', 'otros', 'Me dijo un amigo', '2014-05-12 21:08:03'),
(5, 1, 'Rafaela', 'Rocha', 'Ruiz', 'Alquimia', 'Norberto Ruiz', '40', '5551876838', 'rafaela@gmail.com', 'mujer', '1974-11-21', '90', 'tv', '', '2014-05-12 21:14:39'),
(6, 4, 'Alfonso', 'Fernandez', 'Maduro', 'Gilberto Ramirez', 'Noa', '3', '9', 'fernandez@hotmail.com', 'hombre', '1986-05-15', '23', 'radio', '', '2014-05-12 21:17:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `id_proceso` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  PRIMARY KEY (`id_permiso`),
  KEY `id_proceso` (`id_proceso`),
  KEY `id_rol` (`id_rol`),
  KEY `id_modulo` (`id_modulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=64 ;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `id_proceso`, `id_rol`, `id_modulo`) VALUES
(2, 2, 1, 1),
(3, 3, 1, 1),
(4, 4, 1, 1),
(7, 7, 1, 2),
(8, 8, 1, 2),
(9, 25, 1, 2),
(10, 9, 1, 3),
(11, 10, 1, 3),
(12, 11, 1, 3),
(14, 13, 1, 3),
(15, 14, 1, 3),
(16, 15, 1, 3),
(17, 16, 1, 3),
(18, 17, 1, 4),
(19, 18, 1, 4),
(20, 19, 1, 4),
(21, 20, 1, 4),
(22, 21, 1, 4),
(23, 22, 1, 4),
(24, 23, 1, 4),
(26, 26, 1, 5),
(27, 27, 1, 3),
(28, 12, 1, 3),
(33, 5, 1, 1),
(40, 1, 1, 1),
(42, 24, 1, 4),
(43, 30, 1, 1),
(44, 31, 1, 1),
(45, 32, 1, 2),
(46, 33, 1, 3),
(47, 34, 1, 3),
(48, 35, 1, 4),
(49, 36, 1, 4),
(50, 26, 2, 5),
(54, 6, 1, 2),
(55, 37, 1, 5),
(56, 38, 1, 1),
(57, 39, 1, 4),
(58, 40, 1, 3),
(59, 41, 1, 6),
(60, 42, 1, 6),
(61, 43, 1, 6),
(62, 44, 1, 6),
(63, 45, 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE IF NOT EXISTS `procesos` (
  `id_proceso` int(11) NOT NULL AUTO_INCREMENT,
  `id_modulo` int(11) DEFAULT NULL,
  `proceso` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `nombre_proceso` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id_proceso`),
  KEY `id_modulo` (`id_modulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=46 ;

--
-- Volcado de datos para la tabla `procesos`
--

INSERT INTO `procesos` (`id_proceso`, `id_modulo`, `proceso`, `nombre_proceso`) VALUES
(1, 1, 'listado', 'Listado'),
(2, 1, 'alta', 'Nuevo'),
(3, 1, 'eliminar', 'Eliminar'),
(4, 1, 'busqueda', 'Busqueda'),
(5, 1, 'proceso', 'Ver Procesos'),
(6, 2, 'alta', 'Nuevo'),
(7, 2, 'edicion', 'Editar'),
(8, 2, 'eliminar', 'Eliminar'),
(9, 3, 'listado', 'Listado'),
(10, 3, 'alta', 'Nuevo'),
(11, 3, 'edicion', 'Editar'),
(12, 3, 'eliminar', 'Eliminar'),
(13, 3, 'busqueda', 'Busqueda'),
(14, 3, 'cambiar_permiso', 'Modificar Permisos'),
(15, 3, 'cambiar_estado', 'Modificar Estado'),
(16, 3, 'permisos', 'Ver Permisos Asignados'),
(17, 4, 'listado', 'Listado'),
(18, 4, 'alta', 'Nuevo'),
(19, 4, 'comprobar_correo', 'Verificar Correo Electrónico'),
(20, 4, 'eliminar', 'Eliminar'),
(21, 4, 'cambiar_estado', 'Modificar Estado'),
(22, 4, 'cambiarContrasena', 'Modificar Contraseña'),
(23, 4, 'edicion', 'Buscar'),
(24, 4, 'busqueda', 'Busqueda'),
(25, 2, 'busqueda', 'Busqueda'),
(26, 5, 'index', 'Principal'),
(27, 3, 'busqueda_permisos', 'Buscador de Asignación de Permisos'),
(30, 1, 'index', 'Index'),
(31, 1, 'paginacionMod', 'Paginación'),
(32, 2, 'paginacionPro', 'Paginación'),
(33, 3, 'index', 'Index'),
(34, 3, 'paginacionRol', 'Paginación'),
(35, 4, 'index', 'Index'),
(36, 4, 'paginacionUsu', 'Paginación'),
(37, 5, 'seguridad', 'Módulo Seguridad'),
(38, 1, 'mod', 'Control de Modúlos'),
(39, 4, 'mod', 'Control de Usuarios'),
(40, 3, 'mod', 'Control de Usuarios'),
(41, 6, 'index', 'Principal'),
(42, 6, 'municipios', 'Municipios'),
(43, 6, 'nuevo', 'Nuevo'),
(44, 6, 'paginacionPac', 'Paginación pacientes'),
(45, 6, 'ultimo_registro', 'Ultimo registro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `estado` enum('activo','inactivo') COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`, `descripcion`, `estado`) VALUES
(1, 'SUPER ADMINISTRADOR', 'SUPER ADMINISTRADOR', 'activo'),
(2, 'ADMINISTRADOR', 'ADMINISTRADOR', 'activo'),
(4, 'CAPTURISTA', 'CAPTURISTA', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `password` char(40) COLLATE latin1_spanish_ci NOT NULL DEFAULT '0000000000000000000000000000000000000000',
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ip` char(15) COLLATE latin1_spanish_ci DEFAULT '255.255.255.255',
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_modificado` datetime DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') COLLATE latin1_spanish_ci DEFAULT 'ACTIVO',
  PRIMARY KEY (`id_usuario`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `password`, `id_rol`, `nombre`, `correo`, `ip`, `ultimo_acceso`, `fecha_modificado`, `estado`) VALUES
(4, 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 'Administrador', 'admin@gmail.com', '::1', '2014-05-13 01:08:41', '2013-03-02 00:00:00', 'ACTIVO');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `estadoId` FOREIGN KEY (`estadoId`) REFERENCES `estados` (`estadoId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `municipioId` FOREIGN KEY (`municipioId`) REFERENCES `municipios` (`municipioId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_proceso`) REFERENCES `procesos` (`id_proceso`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `permisos_ibfk_3` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
