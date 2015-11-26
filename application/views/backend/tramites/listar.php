<div class="lista-tramites">
    <form id="form-filtros" action="<?php echo site_url('backend/tramites'); ?>" method="get" class="form form-horizontal">
        <legend>Filtros</legend>
        <div class="control-group">
            <div class="control-label">
                <label for="instituciones">Institución</label>
            </div>
            <div class="controls">
                <select  class="input-xxlarge" name="instituciones" id="instituciones">
                    <option value="">-Seleccione-</option>
                    <?php foreach ($instituciones as $key => $institucion){ ?>
                        <option <?php echo $selectedInstitucion==md5($institucion->institucion)?'selected="selected"':''; ?> value="<?php echo md5($institucion->institucion); ?>"><?php echo $institucion->institucion; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="tramite">Trámite</label>
            </div>
            <div class="controls">
                <input type="text" class="input-xxlarge" name="tramite" id="tramite" value="<?php echo $tramite; ?>">
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="codigo">Código</label>
            </div>
            <div class="controls">
                <input type="text" class="input-medium" name="codigo" id="codigo" value="<?php echo $codigo; ?>">
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="digitalizados">Digitalizados</label>
            </div>
            <div class="controls">
                <select  class="input-medium" name="digitalizados" id="digitalizados">
                    <option value="">-Todos-</option>
                    <option value="si" <?php echo $digitalizados=='si'?'selected="selected"':''; ?>>Si</option>
                    <option value="no" <?php echo $digitalizados=='no'?'selected="selected"':''; ?>>No</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="sello_chilesinpapeleo">Sello Chilesinpapeleo</label>
            </div>
            <div class="controls">
                <select  class="input-medium" name="sello_chilesinpapeleo" id="sello_chilesinpapeleo">
                    <option value="">-Todos-</option>
                    <option value="si" <?php echo $sello_chilesinpapeleo=='si'?'selected="selected"':''; ?>>Si</option>
                    <option value="no" <?php echo $sello_chilesinpapeleo=='no'?'selected="selected"':''; ?>>No</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
        <input type="hidden" name="orderby" id="sort-field" value="<?php echo $orderby; ?>">
        <input type="hidden" name="orderdir" id="sort-direction" value="<?php echo $orderdir; ?>">
    </form>
    <hr>
        <a href="<?php echo site_url('backend/tramites/nuevo'); ?>" class="btn btn-primary">Nuevo Trámite</a>
    <hr>
    <?php echo $pagination; ?>
    <table class="table table-striped sort-table" data-form-filter="form-filtros">
        <thead>
            <tr>
                <th class="sort-field" data-filter-name="tramite">Trámite (Codigo Api Chileatiende)</th>
                <th class="sort-field" data-filter-name="institucion">Institución</th>
                <th class="sort-field" data-filter-name="cant_registros">Denuncias</th>
                <th class="sort-field" data-filter-name="sello_chilesinpapeleo">Sello Chilesinpapeleo</th>
                <th class="sort-field" data-filter-name="digitalizado">Digitalizado</th>
                <th class="sort-field" data-filter-name="digitalizacion_proactiva">Digitalización Proactiva</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($tramites): ?>
                <?php foreach ($tramites as $key => $tramite){ ?>
                    <tr>
                        <td><?php echo $tramite->tramite; ?> (<?php echo $tramite->origen; ?>)</td>
                        <td><?php echo $tramite->institucion; ?></td>
                        <td><?php echo $tramite->cant_registros; ?></td>
                        <td><?php echo $tramite->sello_chilesinpapeleo?'Si':'No'; ?></td>
                        <td><?php echo $tramite->digitalizado?'Si':'No'; ?></td>
                        <td><?php echo $tramite->digitalizacion_proactiva?'Si':'No'; ?></td>
                        <td nowrap>
                            <a class="btn btn-small btn-success" href="<?php echo site_url('backend/tramites/editar/'.$tramite->id_tramite); ?>"><i class="icon-edit icon-white"></i> Editar</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No se han encontrado trámites.</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
    <?php echo $pagination; ?>
</div>