-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-06-2020 a las 06:38:31
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lavasecoexprex`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` text NOT NULL DEFAULT '',
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactenos`
--

CREATE TABLE `contactenos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `correo` text NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuadrar_caja`
--

CREATE TABLE `cuadrar_caja` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `valor` decimal(10,0) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `fk_cliente` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `total` decimal(10,0) NOT NULL DEFAULT 0,
  `obono` decimal(10,0) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_productos`
--

CREATE TABLE `facturas_productos` (
  `id` int(11) NOT NULL,
  `fk_factura` int(11) NOT NULL DEFAULT 0,
  `fk_producto` int(11) NOT NULL DEFAULT 0,
  `tipo_servicio` int(11) NOT NULL DEFAULT 0,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `precio` decimal(10,0) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id` int(11) NOT NULL,
  `fk_tipo_gatos` int(11) NOT NULL DEFAULT 0,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `precio` decimal(10,0) NOT NULL DEFAULT 0,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_tipo`
--

CREATE TABLE `gastos_tipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT '0',
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `nombre_tabla` varchar(100) NOT NULL,
  `id_registro` int(11) NOT NULL DEFAULT 0,
  `accion` varchar(100) NOT NULL,
  `fk_usuario` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `tag` mediumtext NOT NULL,
  `icono` mediumtext DEFAULT NULL,
  `ruta` mediumtext DEFAULT NULL,
  `fk_modulo_tipo` int(11) NOT NULL DEFAULT 0,
  `fk_modulo` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id`, `nombre`, `tag`, `icono`, `ruta`, `fk_modulo_tipo`, `fk_modulo`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'usuarios', 'Usuarios', 'fas fa-users', 'usuarios/', 1, 0, '2020-05-11 20:12:04', 1, 1),
(2, 'modulos', 'Modulos', 'fab fa-modx', 'modulos/', 1, 0, '2020-05-11 20:13:05', 1, 1),
(7, 'productos', 'Productos', 'fas fa-shopping-bag', 'productos/', 1, 0, '2020-05-12 16:52:45', 1, 1),
(8, 'gastos', 'Gastos', 'fas fa-file-invoice-dollar', 'gastos', 1, 0, '2020-06-28 21:21:32', 1, 1),
(9, 'clientes', 'Clientes', 'far fa-address-book', 'clientes', 1, 0, '2020-06-28 22:23:10', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_tipo`
--

CREATE TABLE `modulo_tipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `modulo_tipo`
--

INSERT INTO `modulo_tipo` (`id`, `nombre`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 'Modulo', '2020-05-11 10:44:37', 1, 1),
(2, 'Permiso', '2020-05-11 10:44:37', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT '0',
  `precio` decimal(10,0) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `nombres`, `apellidos`, `correo`, `password`, `estado`, `fecha_creacion`, `fk_creador`) VALUES
(1, 'admin', 'admin', 'adminitrador', 'admin@admin.com', '$2y$15$9YMc8bl5ElonJ1WqBylenOAZPans5QoPDNKa314UkMf2PTI4zVyP6', 1, '2020-05-04 01:49:03', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_modulos`
--

CREATE TABLE `usuarios_modulos` (
  `id` int(11) NOT NULL,
  `fk_modulo` int(11) NOT NULL DEFAULT 0,
  `fk_usuario` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL,
  `fk_creador` int(11) NOT NULL DEFAULT 0,
  `estado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios_modulos`
--

INSERT INTO `usuarios_modulos` (`id`, `fk_modulo`, `fk_usuario`, `fecha_creacion`, `fk_creador`, `estado`) VALUES
(1, 1, 1, '2020-05-11 20:42:04', 1, 1),
(2, 2, 1, '2020-05-11 21:39:46', 1, 1),
(3, 7, 1, '2020-06-28 19:57:16', 1, 1),
(4, 8, 1, '2020-06-28 21:24:26', 1, 1),
(5, 9, 1, '2020-06-28 22:23:17', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contactenos`
--
ALTER TABLE `contactenos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuadrar_caja`
--
ALTER TABLE `cuadrar_caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas_productos`
--
ALTER TABLE `facturas_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gastos_tipo`
--
ALTER TABLE `gastos_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulo_tipo`
--
ALTER TABLE `modulo_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contactenos`
--
ALTER TABLE `contactenos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuadrar_caja`
--
ALTER TABLE `cuadrar_caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas_productos`
--
ALTER TABLE `facturas_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gastos_tipo`
--
ALTER TABLE `gastos_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `modulo_tipo`
--
ALTER TABLE `modulo_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
