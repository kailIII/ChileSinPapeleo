<?php
$excel_array = array(
		1 => array(
			'Id',
			'Fecha',
			'Origen',
			'Edad',
			'Sexo',
			'Tipo',
			'Mensaje',
			'Razón',
			'Actividad',
			'Traba',
			'Solución',
			'Servicios Relacionados',
			'Otros Servicios Relacionados',
			'Correo de Contacto',
			'Rut Empresa',
			'Institución',
			'Trámite',
			'Etapa Empresa',
			'Tamaño Empresa',
			'Canales del Trámite'
		)
	);

foreach ($resultados as $key => $resultado){
	$excel_array[] = array(
			$resultado->id,
			$resultado->created_at,
			$resultado->origen,
			$resultado->edad,
			$resultado->sexo,
			$resultado->tipo=='p'?'Persona':'Empresa',
			$resultado->mensaje,
			$resultado->nombre_razon,
			$resultado->nombre_actividad,
			$resultado->traba,
			$resultado->solucion,
			$resultado->servicios_relacionados,
			$resultado->otro_servicio_relacionado,
			$resultado->mail_contacto,
			$resultado->rut_empresa,
			$resultado->institucion,
			$resultado->tramite,
			$resultado->etapa_empresa,
			$resultado->nombre_tamano_empresa,
			$resultado->tipo_tramite
		);
}
$this->load->library('Excel_XML');
$xls = new Excel_XML('UTF-8', false, 'Resultados Chile sin papeleo');
$xls->addArray($excel_array);
$xls->generateXML('resultados');
?>