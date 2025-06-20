-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2025 a las 14:25:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bbdd_tenda`
--
CREATE DATABASE IF NOT EXISTS `bbdd_tenda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `bbdd_tenda`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(120) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `token_password` varchar(40) NOT NULL,
  `password_request` tinyint(4) NOT NULL DEFAULT 0,
  `activo` tinyint(4) NOT NULL,
  `fecha_alta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `usuario`, `password`, `nombre`, `email`, `token_password`, `password_request`, `activo`, `fecha_alta`) VALUES
(0, 'admin', '$2y$10$jK.uexMt29CVvKA3xjyxLegPQUH9IKgz5e1/KMgk/.JbHTFNwqwl6', 'Administrador', 'hector19@gmail.com', '', 0, 1, '2025-06-15 17:15:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `descripcion`, `icono`, `color`, `fecha_creacion`) VALUES
(1, 'Electronica ', 'electronica', 'fa-bolt', '#fd990d', '2025-06-16 11:31:54'),
(2, 'Cables', 'cables', 'fa-plug', '#8cb3ed', '2025-06-16 11:41:58'),
(3, 'Hogar inteligente', 'hogar inteligente', 'fa-solid fa-house-signal', '#c64ae8', '2025-06-16 18:03:54'),
(4, 'Accesorios móvil', 'para el móvil', 'fa-solid fa-mobile-screen-button', '#6aec6c', '2025-06-16 18:04:39'),
(5, 'iluminación LED', 'leds', 'fa-solid fa-lightbulb', '#b21f5a', '2025-06-16 18:05:35'),
(6, 'Gaming', 'gaming', 'fa-solid fa-gamepad', '#000000', '2025-06-16 18:05:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `email` varchar(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `estatus` tinyint(4) NOT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_modifica` datetime NOT NULL,
  `fecha_baja` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombres`, `apellidos`, `email`, `telefono`, `dni`, `estatus`, `fecha_alta`, `fecha_modifica`, `fecha_baja`) VALUES
(17, 'Sara', 'Gomez Torres', 'saragt@gmail.com', '678776633', '00000002W', 1, '2025-06-15 21:28:14', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Toni', 'Francos', 'tonifranco@gva.edu.e', '644567898', '87654321X', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Pepe', 'Fas', 'pepe@gmail.com', '906789233', '00000000T', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'María', 'Payá', 'solomery01@gmail.com', '987878787', '12345678Z', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `id_transaccion` varchar(20) NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `id_cliente` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `id_transaccion`, `fecha`, `status`, `email`, `id_cliente`, `total`) VALUES
(35, '8GV7113220478484W', '2025-06-15 12:33:14', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 3333.00),
(36, '32R69546FG029130E', '2025-06-15 13:30:18', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 3333.00),
(37, '72L51230VS498080D', '2025-06-15 23:38:56', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 3333.00),
(38, '30V81525XA620644P', '2025-06-16 17:14:38', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 9505.08),
(39, '6CE11499MP4500926', '2025-06-17 14:34:51', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(40, '6VP02197YT159654N', '2025-06-17 14:35:25', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(41, '01F74279V17176455', '2025-06-17 14:38:30', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(42, '06P77995YY916960M', '2025-06-17 14:39:01', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(43, '3UD16532G43695938', '2025-06-17 14:39:22', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(44, '6JL15671BN2857105', '2025-06-17 14:39:42', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(45, '74500202YG397192A', '2025-06-17 14:40:15', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50),
(46, '60662621S5457363Y', '2025-06-17 14:40:40', 'COMPLETED', 'sb-vplu238166164@personal.example.com', 'F67ZT63F3VNV8', 1666.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `id_compra`, `id_producto`, `nombre`, `precio`, `cantidad`) VALUES
(51, 35, 2, 'Ordenador', 1000.00, 1),
(52, 35, 3, 'iphone', 2333.00, 1),
(53, 36, 2, 'Ordenador', 1000.00, 1),
(54, 36, 3, 'iphone', 2333.00, 1),
(55, 37, 2, 'Ordenador', 1000.00, 1),
(56, 37, 3, 'iphone', 2333.00, 1),
(57, 38, 2, 'Ordenador', 500.00, 5),
(58, 38, 3, 'iphone', 1166.50, 6),
(59, 39, 2, 'Ordenador', 500.00, 1),
(60, 39, 3, 'iphone', 1166.50, 1),
(61, 40, 2, 'Ordenador', 500.00, 1),
(62, 40, 3, 'iphone', 1166.50, 1),
(63, 41, 2, 'Ordenador', 500.00, 1),
(64, 41, 3, 'iphone', 1166.50, 1),
(65, 42, 2, 'Ordenador', 500.00, 1),
(66, 42, 3, 'iphone', 1166.50, 1),
(67, 43, 2, 'Ordenador', 500.00, 1),
(68, 43, 3, 'iphone', 1166.50, 1),
(69, 44, 2, 'Ordenador', 500.00, 1),
(70, 44, 3, 'iphone', 1166.50, 1),
(71, 45, 2, 'Ordenador', 500.00, 1),
(72, 45, 3, 'iphone', 1166.50, 1),
(73, 46, 2, 'Ordenador', 500.00, 1),
(74, 46, 3, 'iphone', 1166.50, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `mensaje`, `fecha`) VALUES
(1, 'Andreu', 'andreu@gmail.com', 'adsafsdfsdfsdfsdf', '2025-06-16 12:40:47'),
(2, 'Pepe', 'blackecomviewer@gmail.com', 'Hola soy pepe y tengo un problema', '2025-06-16 13:06:25'),
(3, 'Sucret', 'sucret@gmail.com', 'SIP', '2025-06-16 19:13:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descuento` tinyint(3) NOT NULL DEFAULT 0,
  `id_categoria` int(11) NOT NULL,
  `activo` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `descuento`, `id_categoria`, `activo`, `categoria_id`) VALUES
(1, 'Zapatos', 'dsdsds', 20.00, 50, 1, 1, 2),
(2, 'Ordenador', 'dsdsdsdsd', 1000.00, 50, 1, 1, 1),
(3, 'iphone', 'iphone', 2333.00, 50, 1, 1, 1),
(5, 'StarHair™ IPL Pro – Depiladora Láser de Luz Pulsada', 'Consigue una piel suave y sin vello desde casa con la depiladora StarHair™ IPL Pro. Su tecnología de luz pulsada intensa (IPL) elimina el vello de raíz de forma indolora, segura y duradera.\r\nIdeal para piernas, axilas, ingles y rostro.\r\nIncluye sensor de piel para máxima protección y un diseño ergonómico que se adapta perfectamente a tu mano.\r\n\r\n¡Regalo incluido! Depiladora facial de precisión para eliminar el vello del labio superior, cejas o mentón.', 12.15, 50, 1, 1, 3),
(11, 'Multímetro digital PRO', 'Descubre el Multímetro digital PRO, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 58.30, 0, 0, 1, 1),
(12, 'Altavoz Bluetooth portátil', 'Descubre el Altavoz Bluetooth portátil, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 10.99, 0, 0, 1, 1),
(13, 'Mini proyector HD', 'Descubre el Mini proyector HD, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 64.67, 0, 0, 1, 1),
(14, 'Cámara de vigilancia WiFi', 'Descubre el Cámara de vigilancia WiFi, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 28.47, 0, 0, 1, 1),
(15, 'Batería externa 20.000mAh', 'Descubre el Batería externa 20.000mAh, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 63.99, 0, 0, 1, 1),
(16, 'Cable USB-C trenzado 2m', 'Descubre el Cable USB-C trenzado 2m, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 29.76, 0, 0, 1, 2),
(17, 'Cable HDMI 4K 1.5m', 'Descubre el Cable HDMI 4K 1.5m, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 27.39, 0, 0, 1, 2),
(18, 'Cable de carga magnético', 'Descubre el Cable de carga magnético, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 54.99, 0, 0, 1, 2),
(19, 'Cable AUX 3.5mm dorado', 'Descubre el Cable AUX 3.5mm dorado, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 29.45, 0, 0, 1, 2),
(20, 'Pack de cables Lightning', 'Descubre el Pack de cables Lightning, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 42.99, 0, 0, 1, 2),
(21, 'Enchufe inteligente WiFi', 'Descubre el Enchufe inteligente WiFi, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 29.99, 0, 0, 1, 3),
(22, 'Sensor de movimiento Smart', 'Descubre el Sensor de movimiento Smart, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 62.99, 0, 0, 1, 3),
(23, 'Cámara interior 360º', 'Descubre el Cámara interior 360º, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 35.36, 0, 0, 1, 3),
(24, 'Interruptor táctil WiFi', 'Descubre el Interruptor táctil WiFi, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 76.99, 0, 0, 1, 3),
(25, 'Soporte plegable universal', 'Descubre el Soporte plegable universal, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 39.99, 0, 0, 1, 4),
(26, 'Funda antichoque transparente', 'Descubre el Funda antichoque transparente, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 20.99, 0, 0, 1, 4),
(27, 'Cargador inalámbrico rápido', 'Descubre el Cargador inalámbrico rápido, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 29.99, 0, 0, 1, 4),
(28, 'Bombilla LED inteligente E27', 'Descubre el Bombilla LED inteligente E27, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 13.32, 0, 0, 1, 5),
(30, 'Panel LED ultrafino 60x60', 'Descubre el Panel LED ultrafino 60x60, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 71.99, 0, 0, 1, 5),
(31, 'Lámpara de escritorio LED', 'Descubre el Lámpara de escritorio LED, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 64.99, 0, 0, 1, 5),
(32, 'Ratón gaming RGB 7200DPI', 'Descubre el Ratón gaming RGB 7200DPI, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 71.99, 0, 0, 1, 6),
(33, 'Teclado mecánico retroiluminado', 'Descubre el Teclado mecánico retroiluminado, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 60.85, 0, 0, 1, 6),
(34, 'Auriculares gaming con micrófono', 'Descubre el Auriculares gaming con micrófono, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 23.99, 0, 0, 1, 6),
(35, 'Gamepad inalámbrico multi', 'Descubre el Gamepad inalámbrico multi, ideal para quienes buscan calidad, diseño moderno y máxima funcionalidad. Fabricado con materiales duraderos y pensado para facilitar tu día a día.', 59.99, 0, 0, 1, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activacion` int(11) NOT NULL DEFAULT 0,
  `token` varchar(40) NOT NULL,
  `token_password` varchar(40) DEFAULT NULL,
  `password_request` int(11) NOT NULL DEFAULT 0,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `activacion`, `token`, `token_password`, `password_request`, `id_cliente`) VALUES
(14, 'Andreu22', '$2y$10$GGWf8hZxKfJ7oTqb/QYv7emUch4J6X0x/KJY8w9aAChwj6eBr9nUi', 1, '4c4d73206edca297e80da617a3f0163f', NULL, 0, 14),
(15, 'Pepe22', '$2y$10$6NXICSslOflNQE96wRaPuOp82UdRUkFLDVn5XyVz2RTJhaiyovQei', 1, 'ae872b5b80fb3424cb5b1e5222268f4c', NULL, 0, 15),
(16, 'Cenicera05', '$2y$10$TGDaZzwNXt2.rEBhvMc4OO5tINyiX7i9/jKpKN2DjOYzvQp8zsCuC', 1, '24298c1f17f4a44ec4fcb05317ae9ea7', NULL, 0, 17);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria_producto` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria_producto` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
