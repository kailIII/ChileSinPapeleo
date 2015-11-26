ALTER TABLE tramites ADD sello_chilesinpapeleo BOOLEAN NULL DEFAULT FALSE;
ALTER TABLE tramites ADD fecha_publicacion_guia_online DATETIME NULL DEFAULT NULL;

UPDATE tramites SET sello_chilesinpapeleo = digitalizado;
UPDATE tramites SET digitalizado = 0;

--UNA VEZ ACTUALIZADA LAS FECHA DE LA GUIA ONLINE, SE EJECUTA EL SIGUIENTE UPDATE
-- UPDATE tramites AS t SET t.digitalizado = 1 WHERE t.fecha_publicacion_guia_online IS NOT NULL;

--
-- Estructura de tabla para la tabla `resultado_digitalizacion_mejoras`
--

CREATE TABLE IF NOT EXISTS `resultado_digitalizacion_mejoras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `origen` varchar(16) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  `mensaje` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `razones_id` int(11) DEFAULT NULL,
  `actividades_id` int(11) DEFAULT NULL,
  `traba` text,
  `solucion` text,
  `tamano_empresa` int(11) DEFAULT NULL,
  `servicios_relacionados` varchar(255) DEFAULT NULL,
  `otro_servicio_relacionado` varchar(255) DEFAULT NULL,
  `traba_absurda` char(1) DEFAULT NULL,
  `mail_contacto` varchar(255) DEFAULT NULL,
  `rut_empresa` varchar(255) DEFAULT NULL,
  `institucion` varchar(255) DEFAULT NULL,
  `tramite` varchar(255) DEFAULT NULL,
  `etapas_empresa_id` int(11) DEFAULT NULL,
  `tipo_tramite` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_resultado_digitalizacion_mejoras_razones` (`razones_id`),
  KEY `fk_resultado_digitalizacion_mejoras_actividades1` (`actividades_id`),
  KEY `fk_resultado_digitalizacion_mejoras_etapas_empresa1` (`etapas_empresa_id`),
  KEY `origen` (`origen`),
  KEY `tramite` (`tramite`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `resultado_digitalizacion_mejoras`
--
ALTER TABLE `resultado_digitalizacion_mejoras`
  ADD CONSTRAINT `fk_resultado_digitalizacion_mejoras_actividades1` FOREIGN KEY (`actividades_id`) REFERENCES `actividades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_resultado_digitalizacion_mejoras_etapas_empresa1` FOREIGN KEY (`etapas_empresa_id`) REFERENCES `etapas_empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_resultado_digitalizacion_mejoras_razones` FOREIGN KEY (`razones_id`) REFERENCES `razones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
