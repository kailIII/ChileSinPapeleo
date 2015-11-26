<form class="form" action="<?= site_url($form_action) ?>" method="post">
    <fieldset>
        <div class="control-group">
            <label for="titulo" class="control-label">Título</label>
            <div class="controls">
                <input type="text" id="titulo" name="titulo" class="input-xlarge" value="<?= isset($pagina->titulo) ? $pagina->titulo : '' ?>">
                <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
            </div>
        </div>
        <div class="control-group">
            <label for="subtitulo" class="control-label">Subtítulo</label>
            <div class="controls">
                <input type="text" id="subtitulo" name="subtitulo" class="input-xlarge" value="<?= isset($pagina->subtitulo) ? $pagina->subtitulo : '' ?>">
                <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
            </div>
        </div>
        <div class="control-group">
            <label for="contenido" class="control-label">Contenido</label>
            <div class="controls">
                <textarea class="input-xlarge" id="contenido" name="contenido" rows="5"><?= isset($pagina->contenido) ? $pagina->contenido : '' ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a href="<?php echo site_url('/backend/paginas'); ?>" class="btn">Cancelar</a>
        </div>
    </fieldset>
</form>
<script type="text/javascript">
$(document).ready(
    function() {
        $('#contenido').redactor();
    }
);
</script>