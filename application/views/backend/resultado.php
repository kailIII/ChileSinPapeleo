<div class="page-header">
    <h1>Resultados <small>Listado de denuncias.</small></h1>
    <h4><em>Total de resultados: <?php echo $total_resultados; ?></em></h4>
    <h4><em>Total de resultados filtrados: <?php echo $total; ?></em></h4>
</div>
<form class="form-horizontal" action="<?php echo current_url(); ?>" method="post" id="filtros_resultados">
	<fieldset>
		<legend>Filtros</legend>
		<div class="control-group">
			<label for="tipo" class="control-label">Tipo</label>
			<div class="controls">
				<select name="tipo" id="tipo" class="filtro-busqueda">
					<option value="">- Todos -</option>
					<option value="p" <?php echo $tipo=='p'?'selected="selected"':''; ?>>Persona</option>
					<option value="e" <?php echo $tipo=='e'?'selected="selected"':''; ?>>Empresa</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="razon">Razón</label>
			<div class="controls">
				<select name="razon" id="razon" class="filtro-busqueda">
					<option value="">- Todos -</option>
					<?php foreach ($razones as $key => $option_razon){ ?>
						<option <?php echo $razon==$option_razon->id?'selected="selected"':''; ?> value="<?php echo $option_razon->id; ?>"><?php echo $option_razon->razon; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
        <div class="control-group">
            <label class="control-label" for="canal">Canal</label>
            <div class="controls">
                <select name="canal" id="canal" class="filtro-busqueda">
                    <option value="">- Todos -</option>
                    <option value="oficina"<?php echo $canal=='oficina'?' selected="selected"':''; ?>>Oficina</option>
                    <option value="online"<?php echo $canal=='online'?' selected="selected"':''; ?>>Online</option>
                    <option value="correo"<?php echo $canal=='correo'?' selected="selected"':''; ?>>Correo</option>
                    <option value="telefono"<?php echo $canal=='telefono'?' selected="selected"':''; ?>>Teléfono</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tipo_denuncia">Tipo denuncia</label>
            <div class="controls">
                <select name="tipo_denuncia" id="tipo_denuncia" class="filtro-busqueda">
                    <option value="">- Todas -</option>
                    <option value="o"<?php echo $tipo_denuncia=='o'?' selected="selected"':''; ?>>Trámite online</option>
                    <option value="m"<?php echo $tipo_denuncia=='m'?' selected="selected"':''; ?>>Mejora de trámite</option>
                </select>
            </div>
        </div>
		<div class="control-group">
			<label class="control-label" for="institucion">Institución</label>
			<div class="controls">
				<select name="institucion" id="institucion" class="filtro-busqueda">
					<option value="">- Todas -</option>
					<?php foreach ($instituciones as $key => $option_institucion){ ?>
						<option <?php echo $institucion==$option_institucion->institucion?'selected="selected"':''; ?> value="<?php echo $option_institucion->institucion; ?>"><?php echo $option_institucion->institucion; ?> (<?php echo $option_institucion->cant_registros; ?>)</option>
					<?php } ?>
				</select>
			</div>
		</div>
        <?php if ($institucion): ?>
            <div class="control-group">
                <label class="control-label" for="origen">Tramite</label>
                <div class="controls">
                    <select name="origen" id="origen" class="filtro-busqueda">
                        <option value="">- Todos -</option>
                        <?php foreach ($tramites as $key => $tramite){ ?>
                            <option <?php echo $origen==$tramite->origen?'selected="selected"':''; ?> value="<?php echo $tramite->origen; ?>"><?php echo $tramite->tramite; ?> (<?php echo $tramite->cant_registros; ?>)</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php endif ?>
		<input type="hidden" id="filtrar" name="filtrar" value="1">
		<input type="hidden" name="sort-field" id="sort-field" value="<?php echo $sort_field; ?>">
		<input type="hidden" name="sort-direction" id="sort-direction" value="<?php echo $sort_direction; ?>">
		<input type="hidden" name="exportar" id="exportar" value="">
		<div class="offset6">
			<button class="btn btn-primary" id="btn-filtrar">Filtrar</button>
			<button class="btn btn-success" id="btn-exportar">Exportar a Excel</button>
		</div>
	</fieldset>
</form>
<table class="table table-striped sort-table">
    <thead>
        <tr>
        		<th>&nbsp;</th>
        		<th class="sort-field" data-filter-name="created_at">Fecha Ingreso</th>
            <th class="sort-field" data-filter-name="origen">Trámite</th>
            <th class="sort-field" data-filter-name="tipo">Tipo</th>
            <th class="sort-field" data-filter-name="razones_id">Razón</th>
            <th class="sort-field" data-filter-name="tipo_tramite">Canal</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($resultados as $key => $resultado) {
            ?>
            <tr>
            		<td><?php echo $key+1; ?></td>
            		<td><?php echo date('d-m-Y',strtotime($resultado->created_at)); ?></td>
                <td><?php echo $resultado->tramite; ?><?php echo $resultado->origen?' ['.$resultado->origen.']':''; ?></td>
                <td><?php echo $resultado->tipo=='p'?'Persona':'Empresa'; ?></td>
                <td><?php echo $resultado->nombre_razon; ?></td>
                <td><?php echo $resultado->tipo_tramite; ?></td>
                <td><a href="<?php echo site_url('backend/ver_resultado/'.$resultado->id); ?>" class="modal-content btn btn-success">Ver Más</a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<div class="pagination">
	<?php echo $pagination; ?>
</div>
<div id="modal-container" class="modal hide">
	<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Resultado</h3>
  </div>
  <div class="modal-body">
	  
	</div>
  <div class="modal-footer">
  	<button data-dismiss="modal" class="btn">Cerrar</button>
  </div>
</div>