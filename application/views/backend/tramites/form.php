<div class="form-tramite">
    <?php if (isset($error)): ?>
        <div class="alert"><?php echo $error_msg; ?></div>
    <?php endif ?>
    <legend>Tramite</legend>
    <form action="<?php echo site_url('backend/tramites/grabar/'.$tramite->id); ?>" method="post" class="form form-horizontal">
        <div class="control-group">
            <div class="control-label">
                <label for="codigo">Codigo</label>
            </div>
            <div class="controls">
                <input type="text" class="input-medium" name="codigo" id="codigo" value="<?php echo $tramite->codigo; ?>">
                <button class="btn btn-small btn-success" id="trae-info-ficha-chileatiende">Traer datos desde Chileatiende</button>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="nombre">Nombre</label>
            </div>
            <div class="controls">
                <input type="text" class="input-xxlarge" name="nombre" id="nombre" value="<?php echo $tramite->nombre; ?>">
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="institucion">Institución</label>
            </div>
            <div class="controls">
                <input type="text" class="input-xxlarge" name="institucion" id="institucion" value="<?php echo $tramite->institucion; ?>">
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="url">Url</label>
            </div>
            <div class="controls">
                <input type="text" class="input-xxlarge" name="url" id="url" value="<?php echo $tramite->url; ?>">
                <a href="<?php echo $tramite->url; ?>" id="link-chileatiende" target="_blank">Ver en Chileatiende</a>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="sello_chilesinpapeleo">Sello Chilesinpapeleo</label>
            </div>
            <div class="controls">
                <select  class="input-medium" name="sello_chilesinpapeleo" id="sello_chilesinpapeleo">
                    <option value="0">No</option>
                    <option value="1" <?php echo $tramite->sello_chilesinpapeleo?'selected="selected"':''; ?>>Si</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="digitalizado">Digitalizado</label>
            </div>
            <div class="controls">
                <select  class="input-medium" name="digitalizado" id="digitalizado">
                    <option value="0">No</option>
                    <option value="1" <?php echo $tramite->digitalizado?'selected="selected"':''; ?>>Si</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <label for="digitalizacion_proactiva">Digitalización proactiva</label>
            </div>
            <div class="controls">
                <select  class="input-medium" name="digitalizacion_proactiva" id="digitalizacion_proactiva">
                    <option value="0">No</option>
                    <option value="1" <?php echo $tramite->digitalizacion_proactiva?'selected="selected"':''; ?>>Si</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary">Grabar</button>
            </div>
        </div>
    </form>
</div>