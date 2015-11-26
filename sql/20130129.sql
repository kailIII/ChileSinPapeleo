-- Se crea la tabla para los tramites
CREATE TABLE  `tramites` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`codigo` VARCHAR( 255 ) NOT NULL ,
`nombre` VARCHAR( 255 ) NULL DEFAULT  NULL,
`institucion` VARCHAR( 255 ) NULL DEFAULT  NULL,
`url` VARCHAR( 255 ) NULL DEFAULT  NULL,
`digitalizado` BOOLEAN NULL DEFAULT  '0',
`digitalizacion_proactiva` BOOLEAN NULL DEFAULT  '0'
) ENGINE = INNODB;

-- Se deja el codigo como indice único
ALTER TABLE  `tramites` ADD UNIQUE (
`codigo`
);

--
INSERT INTO tramites (codigo, nombre)
SELECT rd.origen, rd.tramite 
FROM (SELECT * FROM resultado_digitalizacion ORDER BY id DESC) AS rd WHERE rd.origen IS NOT NULL AND rd.origen <> '' GROUP BY rd.origen

-- Se corrigen los textos de los nombres
update `tramites` set `nombre` = replace(`nombre` ,'Ã¡','á');
update `tramites` set `nombre` = replace(`nombre` ,'Ã©','é');
update `tramites` set `nombre` = replace(`nombre` ,'í©','é');
update `tramites` set `nombre` = replace(`nombre` ,'Ã³','ó');
update `tramites` set `nombre` = replace(`nombre` ,'íº','ú');
update `tramites` set `nombre` = replace(`nombre` ,'Ãº','ú');
update `tramites` set `nombre` = replace(`nombre` ,'Ã±','ñ');
update `tramites` set `nombre` = replace(`nombre` ,'í‘','Ñ');
update `tramites` set `nombre` = replace(`nombre` ,'Ã','í');
update `tramites` set `nombre` = replace(`nombre` ,'â€“','–');
update `tramites` set `nombre` = replace(`nombre`,'â€™','\'');
update `tramites` set `nombre` = replace(`nombre`,'â€¦','...');
update `tramites` set `nombre` = replace(`nombre`,'â€“','-');
update `tramites` set `nombre` = replace(`nombre`,'â€œ','"');
update `tramites` set `nombre` = replace(`nombre`,'â€','"');
update `tramites` set `nombre` = replace(`nombre`,'â€˜','\'');
update `tramites` set `nombre` = replace(`nombre`,'â€¢','-');
update `tramites` set `nombre` = replace(`nombre`,'â€¡','c');
update `tramites` set `nombre` = replace(`nombre` ,'Â','');