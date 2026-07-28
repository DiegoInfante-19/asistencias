
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `acreditaciones` (
  `id_acreditaciones` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `id_empresa` bigint(20) UNSIGNED NOT NULL,
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `estatus_acreditacion` varchar(50) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `asistencias` (
  `id_asistencias` bigint(20) UNSIGNED NOT NULL,
  `id_sesiones` bigint(20) UNSIGNED NOT NULL,
  `id_inscripcion_cohortes` bigint(20) UNSIGNED NOT NULL,
  `estado_asistencia` varchar(20) NOT NULL,
  `observacion_asistencia` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cargos` (
  `id_cargo` bigint(20) UNSIGNED NOT NULL,
  `descripcion_cargo` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ciudades` (
  `id_ciudad` bigint(20) UNSIGNED NOT NULL,
  `id_estado` bigint(20) UNSIGNED NOT NULL,
  `nombre_ciudad` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cohortes` (
  `id_cohortes` bigint(20) UNSIGNED NOT NULL,
  `numero_cohorte` varchar(20) NOT NULL,
  `fecha_inicio_cohorte` date NOT NULL,
  `fecha_fin_cohorte` date NOT NULL,
  `descripcion_cohorte` text DEFAULT NULL,
  `estatus_cohorte` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresas` (
  `id_empresa` bigint(20) UNSIGNED NOT NULL,
  `nombre_empresa` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa_personas` (
  `id_empresa_personas` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `id_empresa` bigint(20) UNSIGNED NOT NULL,
  `id_cargo` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `empresa_pnf` (
  `id_empresa_pnf` bigint(20) UNSIGNED NOT NULL,
  `id_empresa` bigint(20) UNSIGNED NOT NULL,
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `tipo_relacion` varchar(100) NOT NULL,
  `observacion_empresa_pnf` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `estados` (
  `id_estado` bigint(20) UNSIGNED NOT NULL,
  `nombre_estado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `estatus_expedientes` (
  `id_estatus_expediente` bigint(20) UNSIGNED NOT NULL,
  `nombre_estatus_expediente` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `grupos_academicos` (
  `id_grupo` bigint(20) UNSIGNED NOT NULL,
  `id_cohortes` bigint(20) UNSIGNED NOT NULL,
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `nivel_academico` varchar(20) NOT NULL,
  `estatus_grupo` varchar(50) NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inscripcion_cohortes` (
  `id_inscripcion_cohortes` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `id_grupo` bigint(20) UNSIGNED NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `estatus_inscripcion_cohortes` varchar(50) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lugar_nacimiento_personas` (
  `id_lugar_nacimiento` bigint(20) UNSIGNED NOT NULL,
  `id_ciudad` bigint(20) UNSIGNED NOT NULL,
  `detalles_adicionales` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `observacion_personas` (
  `id_observacion_personas` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `observacion_personas` text NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `periodo_recesos` (
  `id_periodo_receso` bigint(20) UNSIGNED NOT NULL,
  `nombre_periodo_receso` varchar(100) NOT NULL,
  `fecha_inicio_periodo_receso` date NOT NULL,
  `fecha_fin_periodo_receso` date NOT NULL,
  `descripcion_periodo_receso` text DEFAULT NULL,
  `nivel_periodo_receso` varchar(50) NOT NULL,
  `suspension_actividades` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personas` (
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `cedula_personas` varchar(20) NOT NULL,
  `primer_nombre_personas` varchar(50) NOT NULL,
  `segundo_nombre_personas` varchar(50) DEFAULT NULL,
  `primer_apellido_personas` varchar(50) NOT NULL,
  `segundo_apellido_personas` varchar(50) DEFAULT NULL,
  `sexo_personas` char(1) NOT NULL,
  `fecha_nacimiento_personas` date NOT NULL,
  `id_lugar_nacimiento` bigint(20) UNSIGNED NOT NULL,
  `email_personas` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `persona_formacion_academica` (
  `id_persona_formacion_academica` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `id_titulos_pnf` bigint(20) UNSIGNED DEFAULT NULL,
  `id_titulos` bigint(20) UNSIGNED DEFAULT NULL,
  `observacion_formacion_academica` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pnfs` (
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `nombre_pnf` varchar(100) NOT NULL,
  `descripcion_pnf` text DEFAULT NULL,
  `vigencia_pnf` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `preguntas_secretas` (
  `id_preguntas_secretas` bigint(20) UNSIGNED NOT NULL,
  `id_users` bigint(20) UNSIGNED NOT NULL,
  `pregunta1` varchar(150) NOT NULL,
  `pregunta2` varchar(150) NOT NULL,
  `respuesta1` varchar(150) NOT NULL,
  `respuesta2` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `profesores` (
  `id_profesor` bigint(20) UNSIGNED NOT NULL,
  `id_users` bigint(20) UNSIGNED NOT NULL,
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `nivel_asignado` varchar(20) NOT NULL,
  `fecha_asignacion_profesor` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `profesor_grupo` (
  `id_profesor_grupo` bigint(20) UNSIGNED NOT NULL,
  `id_profesor` bigint(20) UNSIGNED NOT NULL,
  `id_grupo` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion_rol` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sesiones` (
  `id_sesiones` bigint(20) UNSIGNED NOT NULL,
  `id_grupo` bigint(20) UNSIGNED NOT NULL,
  `id_profesor` bigint(20) UNSIGNED NOT NULL,
  `fecha_sesion` datetime NOT NULL,
  `observacion_sesion` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `telefonos_personas` (
  `id_telefonos_personas` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `numero_telefono_personas` varchar(20) NOT NULL,
  `tipo_telefono` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `titulacion_personas` (
  `id_titulacion_personas` bigint(20) UNSIGNED NOT NULL,
  `id_personas` bigint(20) UNSIGNED NOT NULL,
  `id_titulacion` bigint(20) UNSIGNED NOT NULL,
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `id_estatus_expediente` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `titulos` (
  `id_titulos` bigint(20) UNSIGNED NOT NULL,
  `nombre_titulo_base` varchar(100) NOT NULL,
  `nivel_academico` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `titulos_pnf` (
  `id_titulos_pnf` bigint(20) UNSIGNED NOT NULL,
  `id_pnf` bigint(20) UNSIGNED NOT NULL,
  `id_titulo` bigint(20) UNSIGNED NOT NULL,
  `nombre_titulo_pnf` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id_users` bigint(20) UNSIGNED NOT NULL,
  `name_users` varchar(100) NOT NULL,
  `last_name_users` varchar(100) NOT NULL,
  `cedula_users` varchar(20) NOT NULL,
  `email_users` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_users` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `status_users` varchar(20) NOT NULL DEFAULT 'Activo',
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `password_users` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acreditaciones`
  ADD PRIMARY KEY (`id_acreditaciones`),
  ADD KEY `acreditaciones_id_personas_foreign` (`id_personas`),
  ADD KEY `acreditaciones_id_empresa_foreign` (`id_empresa`),
  ADD KEY `acreditaciones_id_pnf_foreign` (`id_pnf`);

ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id_asistencias`),
  ADD KEY `asistencias_id_sesiones_foreign` (`id_sesiones`),
  ADD KEY `asistencias_id_inscripcion_cohortes_foreign` (`id_inscripcion_cohortes`);

ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`),
  ADD UNIQUE KEY `cargos_descripcion_cargo_unique` (`descripcion_cargo`);

ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id_ciudad`),
  ADD KEY `ciudades_id_estado_foreign` (`id_estado`);

ALTER TABLE `cohortes`
  ADD PRIMARY KEY (`id_cohortes`),
  ADD UNIQUE KEY `cohortes_numero_cohorte_unique` (`numero_cohorte`);

ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `empresas_nombre_empresa_unique` (`nombre_empresa`);

ALTER TABLE `empresa_personas`
  ADD PRIMARY KEY (`id_empresa_personas`),
  ADD KEY `empresa_personas_id_personas_foreign` (`id_personas`),
  ADD KEY `empresa_personas_id_empresa_foreign` (`id_empresa`),
  ADD KEY `empresa_personas_id_cargo_foreign` (`id_cargo`);

ALTER TABLE `empresa_pnf`
  ADD PRIMARY KEY (`id_empresa_pnf`),
  ADD KEY `empresa_pnf_id_empresa_foreign` (`id_empresa`),
  ADD KEY `empresa_pnf_id_pnf_foreign` (`id_pnf`);

ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `estados_nombre_estado_unique` (`nombre_estado`);

ALTER TABLE `estatus_expedientes`
  ADD PRIMARY KEY (`id_estatus_expediente`),
  ADD UNIQUE KEY `estatus_expedientes_nombre_estatus_expediente_unique` (`nombre_estatus_expediente`);

ALTER TABLE `grupos_academicos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `uk_grupo_unico` (`id_cohortes`,`id_pnf`,`nivel_academico`),
  ADD KEY `grupos_academicos_id_pnf_foreign` (`id_pnf`);

ALTER TABLE `inscripcion_cohortes`
  ADD PRIMARY KEY (`id_inscripcion_cohortes`),
  ADD KEY `inscripcion_cohortes_id_personas_foreign` (`id_personas`),
  ADD KEY `inscripcion_cohortes_id_grupo_foreign` (`id_grupo`);

ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `lugar_nacimiento_personas`
  ADD PRIMARY KEY (`id_lugar_nacimiento`),
  ADD KEY `lugar_nacimiento_personas_id_ciudad_foreign` (`id_ciudad`);

ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `observacion_personas`
  ADD PRIMARY KEY (`id_observacion_personas`),
  ADD KEY `observacion_personas_id_personas_foreign` (`id_personas`);

ALTER TABLE `periodo_recesos`
  ADD PRIMARY KEY (`id_periodo_receso`);

ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_personas`),
  ADD UNIQUE KEY `personas_cedula_personas_unique` (`cedula_personas`),
  ADD UNIQUE KEY `personas_email_personas_unique` (`email_personas`),
  ADD KEY `personas_id_lugar_nacimiento_foreign` (`id_lugar_nacimiento`);

ALTER TABLE `persona_formacion_academica`
  ADD PRIMARY KEY (`id_persona_formacion_academica`),
  ADD KEY `persona_formacion_academica_id_personas_foreign` (`id_personas`),
  ADD KEY `persona_formacion_academica_id_titulos_pnf_foreign` (`id_titulos_pnf`),
  ADD KEY `persona_formacion_academica_id_titulos_foreign` (`id_titulos`);

ALTER TABLE `pnfs`
  ADD PRIMARY KEY (`id_pnf`),
  ADD UNIQUE KEY `pnfs_nombre_pnf_unique` (`nombre_pnf`);

ALTER TABLE `preguntas_secretas`
  ADD PRIMARY KEY (`id_preguntas_secretas`),
  ADD KEY `preguntas_secretas_id_users_foreign` (`id_users`);

ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`),
  ADD KEY `profesores_id_users_foreign` (`id_users`),
  ADD KEY `profesores_id_pnf_foreign` (`id_pnf`);

ALTER TABLE `profesor_grupo`
  ADD PRIMARY KEY (`id_profesor_grupo`),
  ADD UNIQUE KEY `uk_prof_grupo` (`id_profesor`,`id_grupo`),
  ADD KEY `profesor_grupo_id_grupo_foreign` (`id_grupo`);

ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `roles_nombre_rol_unique` (`nombre_rol`);

ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id_sesiones`),
  ADD KEY `sesiones_id_grupo_foreign` (`id_grupo`),
  ADD KEY `sesiones_id_profesor_foreign` (`id_profesor`);

ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

ALTER TABLE `telefonos_personas`
  ADD PRIMARY KEY (`id_telefonos_personas`),
  ADD KEY `telefonos_personas_id_personas_foreign` (`id_personas`);

ALTER TABLE `titulacion_personas`
  ADD PRIMARY KEY (`id_titulacion_personas`),
  ADD KEY `titulacion_personas_id_personas_foreign` (`id_personas`),
  ADD KEY `titulacion_personas_id_titulacion_foreign` (`id_titulacion`),
  ADD KEY `titulacion_personas_id_pnf_foreign` (`id_pnf`),
  ADD KEY `titulacion_personas_id_estatus_expediente_foreign` (`id_estatus_expediente`);

ALTER TABLE `titulos`
  ADD PRIMARY KEY (`id_titulos`);

ALTER TABLE `titulos_pnf`
  ADD PRIMARY KEY (`id_titulos_pnf`),
  ADD KEY `titulos_pnf_id_pnf_foreign` (`id_pnf`),
  ADD KEY `titulos_pnf_id_titulo_foreign` (`id_titulo`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `users_cedula_users_unique` (`cedula_users`),
  ADD UNIQUE KEY `users_email_users_unique` (`email_users`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_id_rol_foreign` (`id_rol`);

ALTER TABLE `acreditaciones`
  MODIFY `id_acreditaciones` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `asistencias`
  MODIFY `id_asistencias` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `cargos`
  MODIFY `id_cargo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

ALTER TABLE `ciudades`
  MODIFY `id_ciudad` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

ALTER TABLE `cohortes`
  MODIFY `id_cohortes` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `empresas`
  MODIFY `id_empresa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE `empresa_personas`
  MODIFY `id_empresa_personas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `empresa_pnf`
  MODIFY `id_empresa_pnf` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

ALTER TABLE `estados`
  MODIFY `id_estado` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `estatus_expedientes`
  MODIFY `id_estatus_expediente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `grupos_academicos`
  MODIFY `id_grupo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

ALTER TABLE `inscripcion_cohortes`
  MODIFY `id_inscripcion_cohortes` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `lugar_nacimiento_personas`
  MODIFY `id_lugar_nacimiento` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

ALTER TABLE `observacion_personas`
  MODIFY `id_observacion_personas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `periodo_recesos`
  MODIFY `id_periodo_receso` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

ALTER TABLE `personas`
  MODIFY `id_personas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `persona_formacion_academica`
  MODIFY `id_persona_formacion_academica` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `pnfs`
  MODIFY `id_pnf` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `preguntas_secretas`
  MODIFY `id_preguntas_secretas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `profesores`
  MODIFY `id_profesor` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `profesor_grupo`
  MODIFY `id_profesor_grupo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `roles`
  MODIFY `id_rol` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `sesiones`
  MODIFY `id_sesiones` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `telefonos_personas`
  MODIFY `id_telefonos_personas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `titulacion_personas`
  MODIFY `id_titulacion_personas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `titulos`
  MODIFY `id_titulos` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `titulos_pnf`
  MODIFY `id_titulos_pnf` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

ALTER TABLE `users`
  MODIFY `id_users` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `acreditaciones`
  ADD CONSTRAINT `acreditaciones_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`),
  ADD CONSTRAINT `acreditaciones_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`) ON DELETE CASCADE,
  ADD CONSTRAINT `acreditaciones_id_pnf_foreign` FOREIGN KEY (`id_pnf`) REFERENCES `pnfs` (`id_pnf`);

ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_id_inscripcion_cohortes_foreign` FOREIGN KEY (`id_inscripcion_cohortes`) REFERENCES `inscripcion_cohortes` (`id_inscripcion_cohortes`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencias_id_sesiones_foreign` FOREIGN KEY (`id_sesiones`) REFERENCES `sesiones` (`id_sesiones`) ON DELETE CASCADE;

ALTER TABLE `ciudades`
  ADD CONSTRAINT `ciudades_id_estado_foreign` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`);

ALTER TABLE `empresa_personas`
  ADD CONSTRAINT `empresa_personas_id_cargo_foreign` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`),
  ADD CONSTRAINT `empresa_personas_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`),
  ADD CONSTRAINT `empresa_personas_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`) ON DELETE CASCADE;

ALTER TABLE `empresa_pnf`
  ADD CONSTRAINT `empresa_pnf_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`),
  ADD CONSTRAINT `empresa_pnf_id_pnf_foreign` FOREIGN KEY (`id_pnf`) REFERENCES `pnfs` (`id_pnf`);

ALTER TABLE `grupos_academicos`
  ADD CONSTRAINT `grupos_academicos_id_cohortes_foreign` FOREIGN KEY (`id_cohortes`) REFERENCES `cohortes` (`id_cohortes`) ON DELETE CASCADE,
  ADD CONSTRAINT `grupos_academicos_id_pnf_foreign` FOREIGN KEY (`id_pnf`) REFERENCES `pnfs` (`id_pnf`) ON DELETE CASCADE;

ALTER TABLE `inscripcion_cohortes`
  ADD CONSTRAINT `inscripcion_cohortes_id_grupo_foreign` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_academicos` (`id_grupo`),
  ADD CONSTRAINT `inscripcion_cohortes_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`);

ALTER TABLE `lugar_nacimiento_personas`
  ADD CONSTRAINT `lugar_nacimiento_personas_id_ciudad_foreign` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`id_ciudad`);

ALTER TABLE `observacion_personas`
  ADD CONSTRAINT `observacion_personas_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`) ON DELETE CASCADE;


ALTER TABLE `personas`
  ADD CONSTRAINT `personas_id_lugar_nacimiento_foreign` FOREIGN KEY (`id_lugar_nacimiento`) REFERENCES `lugar_nacimiento_personas` (`id_lugar_nacimiento`);

ALTER TABLE `persona_formacion_academica`
  ADD CONSTRAINT `persona_formacion_academica_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`) ON DELETE CASCADE,
  ADD CONSTRAINT `persona_formacion_academica_id_titulos_foreign` FOREIGN KEY (`id_titulos`) REFERENCES `titulos` (`id_titulos`) ON DELETE SET NULL,
  ADD CONSTRAINT `persona_formacion_academica_id_titulos_pnf_foreign` FOREIGN KEY (`id_titulos_pnf`) REFERENCES `titulos_pnf` (`id_titulos_pnf`) ON DELETE SET NULL;

ALTER TABLE `preguntas_secretas`
  ADD CONSTRAINT `preguntas_secretas_id_users_foreign` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

ALTER TABLE `profesores`
  ADD CONSTRAINT `profesores_id_pnf_foreign` FOREIGN KEY (`id_pnf`) REFERENCES `pnfs` (`id_pnf`),
  ADD CONSTRAINT `profesores_id_users_foreign` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

ALTER TABLE `profesor_grupo`
  ADD CONSTRAINT `profesor_grupo_id_grupo_foreign` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_academicos` (`id_grupo`) ON DELETE CASCADE,
  ADD CONSTRAINT `profesor_grupo_id_profesor_foreign` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`) ON DELETE CASCADE;

ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_id_grupo_foreign` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_academicos` (`id_grupo`),
  ADD CONSTRAINT `sesiones_id_profesor_foreign` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`);

ALTER TABLE `telefonos_personas`
  ADD CONSTRAINT `telefonos_personas_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`) ON DELETE CASCADE;

ALTER TABLE `titulacion_personas`
  ADD CONSTRAINT `titulacion_personas_id_estatus_expediente_foreign` FOREIGN KEY (`id_estatus_expediente`) REFERENCES `estatus_expedientes` (`id_estatus_expediente`),
  ADD CONSTRAINT `titulacion_personas_id_personas_foreign` FOREIGN KEY (`id_personas`) REFERENCES `personas` (`id_personas`) ON DELETE CASCADE,
  ADD CONSTRAINT `titulacion_personas_id_pnf_foreign` FOREIGN KEY (`id_pnf`) REFERENCES `pnfs` (`id_pnf`),
  ADD CONSTRAINT `titulacion_personas_id_titulacion_foreign` FOREIGN KEY (`id_titulacion`) REFERENCES `titulos` (`id_titulos`);

ALTER TABLE `titulos_pnf`
  ADD CONSTRAINT `titulos_pnf_id_pnf_foreign` FOREIGN KEY (`id_pnf`) REFERENCES `pnfs` (`id_pnf`),
  ADD CONSTRAINT `titulos_pnf_id_titulo_foreign` FOREIGN KEY (`id_titulo`) REFERENCES `titulos` (`id_titulos`);

ALTER TABLE `users`
  ADD CONSTRAINT `users_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;