--******************************************************--
--CONSULTAS PARA OBTENER LOS DATOS QUE SE DEBEN TRABAJAR--
--******************************************************--
    -- OBTIENE LA FECHA DE PUBLICACION COMO TRAMITE ONLINE

    SELECT t.codigo, fv.id AS primera_version_publicada_guia_onlie, fp.id version_publicada_actual, h.created_at
        FROM chilesinpapeleo.tramites AS t
        LEFT JOIN chileatiende.ficha AS fm ON t.codigo = CONCAT(fm.servicio_codigo, '-', fm.correlativo) AND fm.maestro = 1
        LEFT JOIN chileatiende.ficha AS fv ON fm.id = fv.maestro_id
        LEFT JOIN chileatiende.ficha AS fp ON fm.id = fp.maestro_id AND fp.publicado = 1
        LEFT JOIN chileatiende.historial AS h ON h.ficha_version_id = fv.id
    WHERE 
        fv.id IS NOT NULL AND 
        fv.guia_online_url <> '' AND
        h.descripcion IS NOT NULL AND 
        h.descripcion LIKE '%Versión publicada%' AND
        fp.id IS NOT NULL AND
        fp.guia_online_url <> ''
    GROUP BY t.codigo
    ORDER BY fv.id ASC

    -- Se obtienen los trámites que han tenido canal online y luego lo han quitado
    SELECT t.codigo, t.nombre, fv.id AS primera_version_publicada_guia_onlie, fp.id version_publicada_actual_sin_guia_online, h.created_at
        FROM chilesinpapeleo.tramites AS t
        LEFT JOIN chileatiende.ficha AS fm ON t.codigo = CONCAT(fm.servicio_codigo, '-', fm.correlativo) AND fm.maestro = 1
        LEFT JOIN chileatiende.ficha AS fv ON fm.id = fv.maestro_id
        LEFT JOIN chileatiende.ficha AS fp ON fm.id = fp.maestro_id AND fp.publicado = 1
        LEFT JOIN chileatiende.historial AS h ON h.ficha_version_id = fv.id
    WHERE 
        fv.id IS NOT NULL AND 
        fv.guia_online_url <> '' AND
        h.descripcion IS NOT NULL AND 
        h.descripcion LIKE '%Versión publicada%' AND
        fp.id IS NOT NULL AND
        fp.guia_online_url = ''
    GROUP BY t.codigo
    ORDER BY fv.id ASC
--******************************************************--
--******************************************************--

--SE CREAN LOS NUEVOS CAMPAS PARA EL SELLO DE CHILESINPAPELEO Y LA FECHA DE PUBLICACION ONLINE

ALTER TABLE tramites ADD sello_chilesinpapeleo BOOLEAN NULL DEFAULT FALSE;
ALTER TABLE tramites ADD fecha_publicacion_guia_online DATETIME NULL DEFAULT NULL;
ALTER TABLE resultado_digitalizacion ADD tipo_denuncia VARCHAR(1) NULL DEFAULT 'o';
ALTER TABLE resultado_digitalizacion_mejoras ADD tipo_denuncia VARCHAR(1) NULL DEFAULT 'm';

UPDATE tramites SET sello_chilesinpapeleo = digitalizado;
UPDATE tramites SET digitalizado = 0;

-- SE ACTUALIZA EL CAMPO fecha_publicacion_guia_online CON LA FECHA OBTENIDA
UPDATE chilesinpapeleo.tramites AS t SET t.fecha_publicacion_guia_online = (
    SELECT h.created_at
        FROM chileatiende.ficha AS fm
        LEFT JOIN chileatiende.ficha AS fv ON fm.id = fv.maestro_id
        LEFT JOIN chileatiende.ficha AS fp ON fm.id = fp.maestro_id AND fp.publicado = 1
        LEFT JOIN chileatiende.historial AS h ON h.ficha_version_id = fv.id
    WHERE 
        t.codigo = CONCAT(fm.servicio_codigo, '-', fm.correlativo) AND 
        fm.maestro = 1 AND
        fv.id IS NOT NULL AND 
        fv.guia_online_url <> '' AND
        fv.guia_online <> '' AND
        h.descripcion IS NOT NULL AND 
        h.descripcion LIKE '%Versión publicada%' AND
        fp.id IS NOT NULL AND
        fp.guia_online_url <> '' AND
        fp.guia_online <> ''
    GROUP BY t.codigo
    ORDER BY fv.id ASC
)

--TRÁMITES QUE SE DEBEN CORREGIR
UPDATE chilesinpapeleo.tramites SET fecha_publicacion_guia_online = '2013-05-29 00:00:00' WHERE codigo = 'AD013-14';
UPDATE chilesinpapeleo.tramites SET fecha_publicacion_guia_online = '2013-06-01 00:00:00' WHERE codigo = 'AJ000-30';

--UNA VEZ ACTUALIZADA LAS FECHA DE LA GUIA ONLINE, SE EJECUTA EL SIGUIENTE UPDATE
UPDATE tramites AS t SET t.digitalizado = 1 WHERE t.fecha_publicacion_guia_online IS NOT NULL;

--SE ACTUALIZAN LOS RESULTADOS MARCANDO COMO VOTO "MEJORA" LOS QUE FUERON POSTERIOR A SU PUBLICACION GUIA ONLINE
UPDATE resultado_digitalizacion AS r 
LEFT JOIN tramites AS t ON t.codigo = r.origen 
SET r.tipo_denuncia = 'm' 
WHERE t.fecha_publicacion_guia_online < r.created_at;

--SE ACTUALIZAN LOS NOMBRES DE LAS INSTITUCIONES EN LOS TRAMITES DESDE chileatiende
UPDATE chilesinpapeleo.tramites t 
LEFT JOIN chileatiende.servicio s ON SUBSTRING(t.codigo, 1, 5) = s.codigo
SET t.institucion = s.nombre;

--SE ACTUALIZAN LOS NOMBRES DE LOS TRÁMITES DESDE CHILEATIENDE
UPDATE chilesinpapeleo.tramites t 
LEFT JOIN chileatiende.ficha f ON CONCAT(f.servicio_codigo,'-',f.correlativo) = t.codigo
LEFT JOIN chileatiende.ficha fp ON f.id = fp.maestro_id
SET t.nombre = fp.titulo
WHERE f.maestro = 1 AND f.publicado = 1 AND fp.publicado = 1

--OBTIENE EL TOTAL DE TRAMITES QUE HAN TENIDO VOTACIONES SÓLO DE MEJORAS
SELECT rd.origen, rd.tramite, s.nombre AS servicio, e.nombre AS ministerio, CONCAT('http://www.chileatiende.cl/fichas/ver/',f.id) AS url_chileatiende FROM chilesinpapeleo.resultado_digitalizacion rd
LEFT JOIN chileatiende.servicio s ON SUBSTRING(rd.origen, 1, 5) = s.codigo
LEFT JOIN chileatiende.entidad e ON e.codigo = s.entidad_codigo
LEFT JOIN chileatiende.ficha f ON rd.origen = CONCAT(f.servicio_codigo, '-', f.correlativo)
WHERE rd.id in (
    SELECT distinct(rdm.id) FROM chilesinpapeleo.resultado_digitalizacion rdm WHERE rdm.tipo_denuncia = 'm'
) AND rd.id not in (
    SELECT distinct(rdo.id) FROM chilesinpapeleo.resultado_digitalizacion rdo WHERE rdo.tipo_denuncia = 'o'
)
AND f.maestro = 1 AND f.publicado = 1
GROUP BY rd.origen

--SE ACTUALIZAN LOS TRAMITES REVISADO QUE DEBERÍAN TENER VOTOS DE QUIERO TRAMITE ONLINE
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AC004-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AC004-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO998-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-14';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-21';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-29';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA001-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD015-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD015-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD015-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD009-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ004-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ004-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ004-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AJ004-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AS002-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AR002-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AX001-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ003-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ003-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ003-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AV001-28';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AV001-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC002-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC002-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC002-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC002-13';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC002-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZC002-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC002-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AK008-21';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-15';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-19';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-35';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-37';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-38';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-43';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH004-44';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI002-18';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI002-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI002-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZC005-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZC005-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZC005-12';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC005-13';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZC005-17';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZC005-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZC005-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA002-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA002-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA002-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ005-22';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ005-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE011-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE011-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE011-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE002-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD016-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD016-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-12';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-34';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-42';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-46';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-48';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL003-61';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AM006-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AM006-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AD013-13';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AD013-14';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AD013-17';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AD013-19';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AD013-32';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AD013-44';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZA006-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AI004-15';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI004-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI004-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI004-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI004-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AO004-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO004-14';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO004-23';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AO004-25';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO004-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO004-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO004-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AO004-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZB003-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'ZB004-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK006-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK006-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-26';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-28';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-31';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-42';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-49';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-59';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL005-76';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO005-32';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL006-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ011-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK007-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AM011-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AM011-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AN999-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AI005-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AI005-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AI005-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AY001-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ009-20';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ009-21';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ009-22';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ009-34';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ010-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ010-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AI000-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-14';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-15';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-20';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-23';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-35';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-43';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-53';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-54';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-55';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-57';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-58';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-59';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-63';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-64';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-65';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-66';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AJ000-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK000-18';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AC000-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO000-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AP000-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AW000-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'ZD001-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AA001-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AR006-24';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH012-19';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH012-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH012-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH012-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH012-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AB001-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL008-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL008-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL008-13';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL008-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL008-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-10';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-104';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-107';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-120';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-121';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-17';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-35';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AE006-40';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AE006-41';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-43';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-44';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-47';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-52';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-53';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-58';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-62';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-66';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AE006-67';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-68';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-70';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-75';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-78';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-80';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-84';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE006-92';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-12';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-21';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-23';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-34';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-35';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-36';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-39';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-44';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-45';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-49';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-57';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-58';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-59';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK002-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AB006-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AB006-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AW004-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-152';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-153';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AL007-154';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-159';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AL007-156';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AF999-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL007-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AS004-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AI003-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AT001-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AK004-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH010-36';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH008-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH009-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH009-3';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH009-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH009-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AH001-21';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AN002-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AN002-17';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AN002-27';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AN001-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AN001-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-1';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-2';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-7';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-8';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE008-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-11';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-15';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-16';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-19';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-22';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-34';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-39';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-4';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AO006-5';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AL009-6';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'o' WHERE origen = 'AL009-9';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-20';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-21';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-30';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-40';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-49';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-51';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-59';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-61';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-83';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-84';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-85';
UPDATE resultado_digitalizacion SET tipo_denuncia = 'm' WHERE origen = 'AE003-96';

--SE ACTUALIZAN LAS URL DE LOS TRÁMITES DESDE CHILEAITNEDE
UPDATE chilesinpapeleo.tramites t
LEFT JOIN chileatiende.ficha f ON CONCAT(f.servicio_codigo, '-', f.correlativo) = t.codigo
SET t.url = CONCAT('http://www.chileatiende.cl/fichas/ver/', f.id)
WHERE f.maestro = 1 AND f.publicado = 1;

--SE MARCAN CON SELLO LOS TRÁMITES DESDE CHILEATIENDE
UPDATE chilesinpapeleo.tramites t
LEFT JOIN chileatiende.ficha f ON CONCAT(f.servicio_codigo, '-', f.correlativo) = t.codigo
LEFT JOIN chileatiende.ficha fp ON f.id = fp.maestro_id
SET t.digitalizado = 1, t.sello_chilesinpapeleo = 1
WHERE f.maestro = 1 AND f.publicado = 1 AND fp.publicado = 1 AND fp.guia_online <> '' AND fp.guia_online_url <> '' AND fp.sello_chilesinpapeleo = 1;

--SE ELIMINAN LOS VOTOS DE SPAM
DELETE FROM resultado_digitalizacion 
WHERE tipo_tramite = '' AND tipo = 'p' AND mensaje LIKE '%href%';

--LOS TRAMITES QUE NO TENGA ORIGEN SE PASAN A OFICINA
UPDATE resultado_digitalizacion 
SET tipo_tramite = 'oficina'
WHERE tipo_tramite = '' AND tipo = 'p'