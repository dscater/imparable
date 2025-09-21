-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 21-09-2025 a las 23:50:18
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `imparable_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `monto_apertura` decimal(24,2) NOT NULL,
  `monto_actual` decimal(24,2) NOT NULL,
  `monto_cierre` decimal(24,2) NOT NULL,
  `fecha_apertura` date NOT NULL,
  `fecha_cierre` date NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_movimientos`
--

CREATE TABLE `caja_movimientos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `caja_id` bigint UNSIGNED NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `persona_id` bigint UNSIGNED DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `razon_social`, `alias`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'IMPARABLE', 'IMPARABLE S.A.', 'IMPA', 'logo.png', '2025-09-21 19:04:14', '2025-09-21 21:07:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `venta_id` bigint UNSIGNED NOT NULL,
  `producto_servicio_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `precio` decimal(24,2) NOT NULL,
  `descuento` decimal(8,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(24,2) NOT NULL,
  `usa_lote` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta_lotes`
--

CREATE TABLE `detalle_venta_lotes` (
  `id` bigint UNSIGNED NOT NULL,
  `detalle_venta_id` bigint UNSIGNED NOT NULL,
  `ingreso_prod_detalle_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` json DEFAULT NULL,
  `datos_nuevo` json DEFAULT NULL,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA PERSONA', '{\"ci\": \"111111\", \"id\": 1, \"dir\": \"LOS OLIVOS #3232\", \"fono\": \"77777777\", \"ci_exp\": \"LP\", \"correo\": \"juan@gmail.com\", \"nombre\": \"JUAN\", \"materno\": \"MAMANI\", \"paterno\": \"PERES\", \"created_at\": \"2025-09-21T22:53:05.000000Z\", \"updated_at\": \"2025-09-21T22:53:05.000000Z\", \"fecha_registro\": \"2025-09-21\"}', NULL, 'PERSONAS', '2025-09-21', '18:53:05', '2025-09-21 22:53:05', '2025-09-21 22:53:05'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UNA PERSONA', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"materno\": \"\", \"paterno\": \"PAREDES\", \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:00:57.000000Z\", \"fecha_registro\": \"2025-09-21\"}', NULL, 'PERSONAS', '2025-09-21', '19:00:57', '2025-09-21 23:00:57', '2025-09-21 23:00:57'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO admin ACTUALIZÓ EL REGISTRO DE UNA PERSONA', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 1, \"materno\": \"\", \"paterno\": \"PAREDES\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:00:57.000000Z\", \"fecha_registro\": \"2025-09-21\"}', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 1, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:16:00.000000Z\", \"fecha_registro\": \"2025-09-21\"}', 'PERSONAS', '2025-09-21', '19:16:00', '2025-09-21 23:16:00', '2025-09-21 23:16:00'),
(4, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ EL REGISTRO DE UNA PERSONA ', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 1, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:16:00.000000Z\", \"fecha_registro\": \"2025-09-21\"}', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 0, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:18:12.000000Z\", \"fecha_registro\": \"2025-09-21\"}', 'PERSONAS', '2025-09-21', '19:18:12', '2025-09-21 23:18:12', '2025-09-21 23:18:12'),
(5, 1, 'REESTABLECER', 'EL USUARIO admin REESTABLECIÓ EL REGISTRO DE UNA PERSONA ', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 0, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:18:12.000000Z\", \"fecha_registro\": \"2025-09-21\"}', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 1, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:48:31.000000Z\", \"fecha_registro\": \"2025-09-21\"}', 'PERSONAS', '2025-09-21', '19:48:31', '2025-09-21 23:48:31', '2025-09-21 23:48:31'),
(6, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ EL REGISTRO DE UNA PERSONA ', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 1, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:48:31.000000Z\", \"fecha_registro\": \"2025-09-21\"}', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 0, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:48:58.000000Z\", \"fecha_registro\": \"2025-09-21\"}', 'PERSONAS', '2025-09-21', '19:48:58', '2025-09-21 23:48:58', '2025-09-21 23:48:58'),
(7, 1, 'ELIMINACIÓN PERMANENTE', 'EL USUARIO admin ELIMINÓ PERMANENTEMENTE EL REGISTRO DE UNA PERSONA ', '{\"ci\": \"222222\", \"id\": 2, \"dir\": \"LOS OLIVOS #3222\", \"fono\": \"78787878\", \"ci_exp\": \"LP\", \"correo\": null, \"nombre\": \"CARLOS\", \"status\": 0, \"materno\": \"\", \"paterno\": \"PAREDES3\", \"fecha_nac\": null, \"created_at\": \"2025-09-21T23:00:57.000000Z\", \"updated_at\": \"2025-09-21T23:48:58.000000Z\", \"fecha_registro\": \"2025-09-21\"}', NULL, 'PERSONAS', '2025-09-21', '19:49:37', '2025-09-21 23:49:37', '2025-09-21 23:49:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_productos`
--

CREATE TABLE `ingreso_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `nro_lote` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `proveedor_id` bigint UNSIGNED NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `monto_total` decimal(24,2) NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_prod_detalles`
--

CREATE TABLE `ingreso_prod_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `ingreso_producto_id` bigint UNSIGNED NOT NULL,
  `producto_servicio_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `precio_compra` decimal(8,2) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcions`
--

CREATE TABLE `inscripcions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `cancelado` int NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_pausa` date DEFAULT NULL,
  `dias_pausa` int NOT NULL,
  `justificacion` text COLLATE utf8mb4_unicode_ci,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_31_165641_create_configuracions_table', 1),
(2, '2025_09_21_105914_create_personas_table', 1),
(3, '2025_09_21_105915_create_users_table', 1),
(4, '2025_09_21_105919_create_clientes_table', 1),
(5, '2025_09_21_106000_create_historial_accions_table', 1),
(6, '2025_09_21_140406_create_producto_servicios_table', 1),
(7, '2025_09_21_140817_create_cajas_table', 1),
(8, '2025_09_21_140826_create_proveedors_table', 1),
(9, '2025_09_21_140827_create_ingreso_productos_table', 1),
(10, '2025_09_21_140828_create_ingreso_prod_detalles_table', 1),
(11, '2025_09_21_140835_create_salida_productos_table', 1),
(12, '2025_09_21_140836_create_salida_detalles_table', 1),
(13, '2025_09_21_140939_create_ventas_table', 1),
(14, '2025_09_21_140943_create_detalle_ventas_table', 1),
(15, '2025_09_21_141007_create_disciplinas_table', 1),
(16, '2025_09_21_141008_create_inscripcions_table', 1),
(17, '2025_09_21_142043_create_caja_movimientos_table', 1),
(18, '2025_09_21_143420_create_detalle_venta_lotes_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(800) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `fono`, `fecha_nac`, `correo`, `status`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'JUAN', 'PERES', 'MAMANI', '111111', 'LP', 'LOS OLIVOS #3232', '77777777', NULL, 'juan@gmail.com', 1, '2025-09-21', '2025-09-21 22:53:05', '2025-09-21 22:53:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_servicios`
--

CREATE TABLE `producto_servicios` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio` decimal(24,2) NOT NULL,
  `stock_actual` double DEFAULT NULL,
  `stock_minimo` double DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedors`
--

CREATE TABLE `proveedors` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_representante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono_representante` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_detalles`
--

CREATE TABLE `salida_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `salida_producto_id` bigint UNSIGNED NOT NULL,
  `ingreso_prod_detalle_id` bigint UNSIGNED NOT NULL,
  `cantidad` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_productos`
--

CREATE TABLE `salida_productos` (
  `id` bigint UNSIGNED NOT NULL,
  `cantidad_total` double NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `persona_id` bigint UNSIGNED DEFAULT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acceso` int NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `persona_id`, `usuario`, `password`, `acceso`, `tipo`, `foto`, `fecha_registro`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'admin', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 1, 'ADMINISTRADOR', NULL, '2025-09-21', 1, '2025-09-21 19:04:14', '2025-09-21 19:04:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `total` decimal(24,2) NOT NULL,
  `recibido` decimal(24,2) DEFAULT NULL,
  `cambio_entregado` decimal(24,2) NOT NULL DEFAULT '0.00',
  `fecha` date NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cajas_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `caja_movimientos`
--
ALTER TABLE `caja_movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caja_movimientos_user_id_foreign` (`user_id`),
  ADD KEY `caja_movimientos_caja_id_foreign` (`caja_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clientes_persona_id_foreign` (`persona_id`),
  ADD KEY `clientes_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  ADD KEY `detalle_ventas_producto_servicio_id_foreign` (`producto_servicio_id`);

--
-- Indices de la tabla `detalle_venta_lotes`
--
ALTER TABLE `detalle_venta_lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_venta_lotes_detalle_venta_id_foreign` (`detalle_venta_id`),
  ADD KEY `detalle_venta_lotes_ingreso_prod_detalle_id_foreign` (`ingreso_prod_detalle_id`);

--
-- Indices de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_accions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingreso_productos_proveedor_id_foreign` (`proveedor_id`);

--
-- Indices de la tabla `ingreso_prod_detalles`
--
ALTER TABLE `ingreso_prod_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingreso_prod_detalles_ingreso_producto_id_foreign` (`ingreso_producto_id`),
  ADD KEY `ingreso_prod_detalles_producto_servicio_id_foreign` (`producto_servicio_id`);

--
-- Indices de la tabla `inscripcions`
--
ALTER TABLE `inscripcions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inscripcions_user_id_foreign` (`user_id`),
  ADD KEY `inscripcions_cliente_id_foreign` (`cliente_id`),
  ADD KEY `inscripcions_disciplina_id_foreign` (`disciplina_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto_servicios`
--
ALTER TABLE `producto_servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producto_servicios_nombre_unique` (`nombre`);

--
-- Indices de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salida_detalles`
--
ALTER TABLE `salida_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salida_detalles_salida_producto_id_foreign` (`salida_producto_id`),
  ADD KEY `salida_detalles_ingreso_prod_detalle_id_foreign` (`ingreso_prod_detalle_id`);

--
-- Indices de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_persona_id_foreign` (`persona_id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_user_id_foreign` (`user_id`),
  ADD KEY `ventas_cliente_id_foreign` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `caja_movimientos`
--
ALTER TABLE `caja_movimientos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta_lotes`
--
ALTER TABLE `detalle_venta_lotes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingreso_prod_detalles`
--
ALTER TABLE `ingreso_prod_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcions`
--
ALTER TABLE `inscripcions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto_servicios`
--
ALTER TABLE `producto_servicios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedors`
--
ALTER TABLE `proveedors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salida_detalles`
--
ALTER TABLE `salida_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salida_productos`
--
ALTER TABLE `salida_productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD CONSTRAINT `cajas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `caja_movimientos`
--
ALTER TABLE `caja_movimientos`
  ADD CONSTRAINT `caja_movimientos_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`),
  ADD CONSTRAINT `caja_movimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `clientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_producto_servicio_id_foreign` FOREIGN KEY (`producto_servicio_id`) REFERENCES `producto_servicios` (`id`),
  ADD CONSTRAINT `detalle_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Filtros para la tabla `detalle_venta_lotes`
--
ALTER TABLE `detalle_venta_lotes`
  ADD CONSTRAINT `detalle_venta_lotes_detalle_venta_id_foreign` FOREIGN KEY (`detalle_venta_id`) REFERENCES `detalle_ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_lotes_ingreso_prod_detalle_id_foreign` FOREIGN KEY (`ingreso_prod_detalle_id`) REFERENCES `ingreso_prod_detalles` (`id`);

--
-- Filtros para la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD CONSTRAINT `historial_accions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `ingreso_productos`
--
ALTER TABLE `ingreso_productos`
  ADD CONSTRAINT `ingreso_productos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedors` (`id`);

--
-- Filtros para la tabla `ingreso_prod_detalles`
--
ALTER TABLE `ingreso_prod_detalles`
  ADD CONSTRAINT `ingreso_prod_detalles_ingreso_producto_id_foreign` FOREIGN KEY (`ingreso_producto_id`) REFERENCES `ingreso_productos` (`id`),
  ADD CONSTRAINT `ingreso_prod_detalles_producto_servicio_id_foreign` FOREIGN KEY (`producto_servicio_id`) REFERENCES `producto_servicios` (`id`);

--
-- Filtros para la tabla `inscripcions`
--
ALTER TABLE `inscripcions`
  ADD CONSTRAINT `inscripcions_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `inscripcions_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `inscripcions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `salida_detalles`
--
ALTER TABLE `salida_detalles`
  ADD CONSTRAINT `salida_detalles_ingreso_prod_detalle_id_foreign` FOREIGN KEY (`ingreso_prod_detalle_id`) REFERENCES `ingreso_prod_detalles` (`id`),
  ADD CONSTRAINT `salida_detalles_salida_producto_id_foreign` FOREIGN KEY (`salida_producto_id`) REFERENCES `salida_productos` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
