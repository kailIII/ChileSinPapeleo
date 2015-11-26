<div class="resultado">
	<table class="table table-striped">
		<tbody>
			<tr>
				<td>Fecha</td>
				<td><?php echo $created_at; ?></td>
			</tr>
			<tr>
				<td>Tipo</td>
				<td><?php echo $tipo=='p'?'Persona':'Empresa'; ?></td>
			</tr>
			<?php echo muestraCampo('Institución', $institucion); ?>
			<?php echo muestraCampo('Trámite', $tramite); ?>
			<?php echo muestraCampo('Origen', $origen); ?>
			<?php echo muestraCampo('Edad', $edad); ?>
			<?php echo muestraCampo('Sexo', $sexo); ?>
			<?php echo muestraCampo('Razón', $nombre_razon); ?>
			<?php echo muestraCampo('Mensaje', $mensaje); ?>
			<?php echo muestraCampo('Actividad', $nombre_actividad); ?>
			<?php echo muestraCampo('Obstáculo', $traba); ?>
			<?php echo muestraCampo('Solución propuesta', $solucion); ?>
			<?php echo muestraCampo('Correo de contacto', $mail_contacto); ?>
			<?php echo muestraCampo('Rut Empresa', $rut_empresa); ?>
			<?php echo muestraCampo('Etapa Empresa', $etapa_empresa); ?>
			<?php echo muestraCampo('Tamaño Empresa', $nombre_tamano_empresa); ?>
			<?php echo muestraCampo('Servicios Relacionados', $nombre_servicios_relacionados); ?>
			<?php echo muestraCampo('Otros Servicios Relacionados', $otro_servicio_relacionado); ?>
		</tbody>
	</table>
</div>
<?php
	function muestraCampo($nombre, $valor){
		return $valor?'<tr><td>'.$nombre.'</td><td>'.$valor.'</td></tr>':'';
	}
?>