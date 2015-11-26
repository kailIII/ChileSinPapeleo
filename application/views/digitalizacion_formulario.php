<?php if(isset($errorMsg)){ ?>
<div class="alert alert-error">
  <?php echo $errorMsg; ?>
</div>
<?php } ?>
<p>
	<?php echo $introduccion; ?>
</p>
<form action="<?php echo site_url('digitalizacion/enviar_formulario'); ?>" method="post" id="form-digitalizacion">

	<fieldset>
		<div class="control-group">
			<label class="control-label" for="institucion">Institución</label>
			<div class="controls">
				<?php if($texto_institucion){ ?>
					<span class="input-xxlarge uneditable-input"><?php echo $texto_institucion; ?></span>
					<input type="hidden" class="input-xxlarge" id="institucion" name="institucion" value="<?php echo $texto_institucion; ?>">
				<?php }else{ ?>
					<input type="text" class="input-xxlarge" id="institucion" name="institucion" value="<?php echo isset($institucion)?$institucion:''; ?>">
					<p class="help-block">Nombre de la institución pública donde se realiza el trámite.</p>
				<?php } ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="tramite">Trámite</label>
			<div class="controls">
				<?php if($texto_tramite){ ?>
					<span class="input-xxlarge uneditable-input"><?php echo $texto_tramite; ?></span>
					<input type="hidden" class="input-xxlarge" id="tramite" name="tramite" value="<?php echo $texto_tramite; ?>">
				<?php }else{ ?>
					<input type="text" class="input-xxlarge" id="tramite" name="tramite" value="<?php echo isset($tramite)?$tramite:''; ?>">
					<p class="help-block">Nombre del trámite a realizar. Ej: "Bono de Articulación Financiera (BAF)"</p>
				<?php } ?>
			</div>
		</div>
		<div class="control-group">
			<label for="tipo_tramite" class="control-label">Canal del trámite que deseas denunciar</label>
			<div class="controls">
				<?php if($texto_tramite){ ?>
					<span class="uneditable-input"><?php echo ucfirst($tipo_tramite); ?></span>
					<input type="hidden" id="tipo_tramite" name="tipo_tramite" value="<?php echo $tipo_tramite; ?>">
				<?php }else{ ?>
					<select name="tipo_tramite" id="tipo_tramite">
						<option value="">- Seleccione -</option>
						<option value="online" <?php echo $tipo_tramite=='online'?'selected="selected"':''; ?>>En línea</option>
						<option value="oficina" <?php echo $tipo_tramite=='oficina'?'selected="selected"':''; ?>>Oficina</option>
						<option value="telefono" <?php echo $tipo_tramite=='telefono'?'selected="selected"':''; ?>>Teléfono</option>
						<option value="correo" <?php echo $tipo_tramite=='correo'?'selected="selected"':''; ?>>Correo</option>
					</select>
				<?php } ?>
			</div>
		</div>
		<?php if($tipo == 'p'){ ?>
		<div class="control-group">
			<label class="control-label" for="razon">¿Por qué debiera simplificarse este trámite?</label>
			<div class="controls">
				<select name="razon" id="razon">
    			<option value="0">- Seleccione -</option>
    			<?php foreach ($razones as $key => $razon){ ?>
    				<option value="<?php echo $razon->id; ?>"><?php echo $razon->razon; ?></option>
    			<?php } ?>
    		</select>
    		<span class="help-inline">*</span>
			</div>
		</div>
		<div class="control-group">
			<label for="mensaje" class="control-label">Comentarios:</label>
			<div class="controls">
				<textarea name="mensaje" id="mensaje" class="input-xxlarge" rows="6"><?php echo isset($mensaje)?$mensaje:''; ?></textarea>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<p class="help-block">* Campos obligatorios.</p>
			</div>
		</div>
		<?php } ?>
	</fieldset>

	<?php if($tipo == 'p'){ ?>
		<fieldset>
			<legend><h2>Datos Personales</h2></legend>
			<div class="control-group">
				<label for="edad" class="control-label">Indicanos tu edad</label>
				<div class="controls">
					<input type="number" id="edad" name="edad" value="<?php echo isset($errorMsg)?$edad:''; ?>">
				</div>
			</div>
			<div class="control-group">
	      <label class="control-label">Sexo</label>
	      <div class="controls">
	        <label class="radio">
	          <input type="radio" name="sexo" id="sexo_f" value="F" <?php echo isset($sexo)?($sexo=='F'?'checked="checked"':''):''; ?>>
	          Femenino
	        </label>
	        <label class="radio">
	          <input type="radio" name="sexo" id="sexo_m" value="M" <?php echo isset($sexo)?($sexo=='M'?'checked="checked"':''):''; ?>>
	          Masculino
	        </label>
	      </div>
	    </div>
	    <div class="control-group">
	    	<label class="control-label" for="actividad">Actividad</label>
	    	<div class="controls">
	    		<select name="actividad" id="actividad">
	    			<option value="0">- Seleccione -</option>
	    			<?php foreach ($actividades as $key => $option_actividad){ ?>
	    				<?php $selected = isset($errorMsg)&&$actividad==$option_actividad->id?'selected="selected"':''; ?>
	    				<option <?php echo $selected; ?> value="<?php echo $option_actividad->id; ?>"><?php echo $option_actividad->actividad; ?></option>
	    			<?php } ?>
	    		</select>
	    	</div>
	    </div>
		</fieldset>
	<?php } ?>
	<?php if($tipo == 'e'){ ?>
		<fieldset>
			<legend><h2>Datos del obstáculo</h2></legend>
			<?php /* ?>
			<div class="control-group">
				<label class="checkbox" for="traba_absurda"><input type="checkbox" value="s" name="traba_absurda" id="traba_absurda">Quieres que tu denuncia de traba participe en el concurso "la traba más absurda"</label>
			</div>
			<?php */ ?>
			<div class="control-group">
				<label class="control-label" for="etapa_empresa">¿En qué etapa se encuentra tu Empresa?</label>
				<div class="controls">
					<select name="etapa_empresa" id="etapa_empresa">
						<option value="">- Seleccione -</option>
						<?php foreach ($etapas_empresa as $key => $etapa){ ?>
							<option value="<?php echo $etapa->id; ?>" <?php echo $etapa_empresa==$etapa->id?'selected="selected"':''; ?>><?php echo $etapa->nombre; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="tamano_empresa">¿Cuál es tamaño de la Empresa?</label>
				<div class="controls">
					<select name="tamano_empresa" id="tamano_empresa">
						<option value="">- Seleccione -</option>
						<option value="1" <?php echo $tamano_empresa==1?'selected="selected"':''; ?>>Pequeña</option>
						<option value="2" <?php echo $tamano_empresa==2?'selected="selected"':''; ?>>Mediana</option>
						<option value="3" <?php echo $tamano_empresa==3?'selected="selected"':''; ?>>Grande</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="traba">Describe el obstáculo que afecta tu emprendimiento</label>
				<div class="controls">
					<textarea name="traba" id="traba" class="input-xxlarge" rows="6"><?php echo isset($traba)?$traba:''; ?></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="solucion">Propone una solución</label>
				<div class="controls">
					<textarea name="solucion" id="solucion" class="input-xxlarge" rows="6"><?php echo isset($solucion)?$solucion:''; ?></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="servicio_relacionado">¿Se relaciona este obstáculo con alguno de estos servicios?</label>
				<div class="controls checkbox-wrapper">
					<?php foreach ($checks_servicios_relacionados as $key => $servicio){ ?>
						<?php
							$checked = in_array($servicio->id, $servicios_relacionados)?'checked="checked"':'';
						?>
						<label class="checkbox inline">
							<input <?php echo $checked; ?> type="checkbox" name="servicios_relacionados[]" value="<?php echo $servicio->id; ?>" id="servicio_relacionado_<?php echo $servicio->id; ?>"><?php echo $servicio->nombre; ?>
						</label>
					<?php } ?>
					<input class="block" type="text" name="otro_servicio_relacionado" id="otro_servicio_relacionado" value="<?php echo isset($otro_servicio_relacionado)?$otro_servicio_relacionado:''; ?>">
				</div>
			</div>
			<legend><h2>Datos de contacto</h2></legend>
			<div class="control-group mas_info_traba_absurda">
				<label class="control-label" for="mail_contacto">Indícanos tu correo electrónico de contacto</label>
				<div class="controls">
					<input type="text" id="mail_contacto" name="mail_contacto" value="<?php echo isset($mail_contacto)?$mail_contacto:''; ?>">
				</div>
			</div>
			<div class="control-group mas_info_traba_absurda">
				<label class="control-label" for="rut_empresa">Rut de la empresa</label>
				<div class="controls">
					<input type="text" id="rut_empresa" name="rut_empresa" value="<?php echo isset($rut_empresa)?$rut_empresa:''; ?>">
				</div>
			</div>
		</fieldset>
	<?php } ?>
	<div class="control-group">
		<div class="controls">
			<button class="btn btn-primary">Enviar</button>
		</div>
	</div>
	<input type="hidden" id="origen" name="origen" value="<?php echo $origen; ?>">
    <input type="hidden" id="url_tramite" name="url_tramite" value="<?php echo $url_tramite; ?>">
	<input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>">
    <input type="hidden" id="mejora" name="mejora" value="<?php echo $mejora; ?>">
</form>