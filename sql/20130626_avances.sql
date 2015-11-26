CREATE TABLE IF NOT EXISTS `avance_tramites` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo_servicio` varchar(8) NOT NULL,
  `codigo_chsp` varchar(16) NOT NULL,
  `codigo_cha` varchar(16) NOT NULL,
  `nombre` varchar(512) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `comprometido_1` int(11) DEFAULT NULL,
  `cumplido_1` int(11) DEFAULT NULL,
  `comprometido_2` int(11) DEFAULT NULL,
  `cumplido_2` int(11) DEFAULT NULL,
  `comprometido_3` int(11) DEFAULT NULL,
  `cumplido_3` int(11) DEFAULT NULL,
  `comprometido_4` int(11) DEFAULT NULL,
  `cumplido_4` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;