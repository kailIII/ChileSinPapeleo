<?php
    function getGradoDenuncia($max_denuncias, $cantDenuncias){
        $porcentaje = ($cantDenuncias/$max_denuncias)*100;
        return 'grado-'.($porcentaje>=50?'5':
            ($porcentaje>=30?'4':
                ($porcentaje>=20?'3':
                    ($porcentaje>=10?'2':
                        '1')
                    )
                )
            );
    }
    function getDataRazones($tramite, $razones){
        $aTramite = (array)$tramite;
        foreach ($razones as $key => $razon) {
            $ret[] = 'data-razon-'.$razon->id.'="'.$aTramite["razon_".$razon->id].'"';
        }
        return implode(' ', $ret);
    }
$icon = $orderdir == 'DESC'?'<i class="icon-arrow-down"></i>':'<i class="icon-arrow-up"></i>';
?>
<div class="info-participaciones">
    <p>
        Hemos recibido un total de <span class="label label-info"><?php echo number_format($total_denuncias,0,',', '.'); ?></span> de peticiones ciudadanas para un total de <span class="label label-info"><?php echo $total_instituciones; ?></span> instituciones
        <?php if (!$mejoras): ?>
            <small class="muted">
                Los siguientes son los trámites votados para ser digitalizados.<br/>
                Para ver los trámites que recibieron votos para ser mejorados, <a href="<?php echo site_url('paginas/ver/peticiones-ciudadanas?mejoras=1'); ?>">haz clic aquí</a>
            </small>
        <?php else: ?>
            <small class="muted">
                A continuación se muestran los trámites que han sido votados para que su proceso sea mejorado.</br>
                Para ver los trámites que recibieron votos para ser digitalizados, <a href="<?php echo site_url('paginas/ver/peticiones-ciudadanas'); ?>">haz clic aquí</a>
            </small>
        <?php endif ?>
    </p>
</div>
<div class="contenedor-filtros-tramites row">
    <form action="<?php echo current_url(); ?>" method="GET" id="formSigueElAvance">
        <div class="control-group span6">
            <h3 for="instituciones" class="control-label">Instituciones:</h3>
            <div class="controls">
                <select name="instituciones" id="instituciones">
                    <option value="">- Todas -</option>
                    <?php foreach ($instituciones as $key => $institucion){ ?>
                        <option <?php echo $selectedInstitucion==md5($institucion->institucion)?'selected="selected"':''; ?> value="<?php echo md5($institucion->institucion); ?>"><?php echo $institucion->institucion; ?> (<?php echo $institucion->cant_registros; ?>)</option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="control-group span6">
            <h3 for="instituciones" class="control-label">Ordenar por:</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <button type="button" class="btn btn-small btn-filtro <?php echo $orderby=='cant_registros'?'active':''; ?>" data-filtro="cant_registros" data-direccion="<?php echo $orderdir=="DESC"; ?>">Cantidad <?php echo $orderby=='cant_registros'?$icon:''; ?></button>
                <button type="button" class="btn btn-small btn-filtro <?php echo $orderby=='institucion'?'active':''; ?>" data-filtro="institucion" data-direccion="<?php echo $orderdir=="DESC"; ?>">Institución <?php echo $orderby=='institucion'?$icon:''; ?></button>
                <button type="button" class="btn btn-small btn-filtro <?php echo $orderby=='tramite'?'active':''; ?>" data-filtro="tramite" data-direccion="<?php echo $orderdir=="DESC"; ?>">Trámite <?php echo $orderby=='tramite'?$icon:''; ?></button>
                <?php /* ?>
                <button type="button" class="btn btn-filtro" data-filtro="tipo_tramite">Canal del trámite</button>
                <?php */ ?>
            </div>
        </div>
        <input type="hidden" id="orderby" name="orderby" value="<?php echo $orderby; ?>">
        <input type="hidden" id="orderdir" name="orderdir" value="<?php echo $orderdir; ?>">
        <input type="hidden" id="offset" name="offset" value="<?php echo $offset; ?>">
        <input type="hidden" id="mejoras" name="mejoras" value="<?php echo $mejoras; ?>">
    </form>
</div>
<hr>
<?php echo $pagination; ?>
<br>
<div id="contenedor-tramites">
<?php foreach ($tramites as $key => $tramite){ ?>
    <?php
        $canales = ($tramite->oficina>0?' oficina':'').($tramite->online?' online':'').($tramite->correo?' correo':'').($tramite->telefono?' telefono':'').($tramite->nodefinido?' sin-definir':'');
        $dataCanales = ' data-oficina="'.$tramite->oficina.'" data-online="'.$tramite->online.'" data-correo="'.$tramite->correo.'" data-telefono="'.$tramite->telefono.'" data-nodefinido="'.$tramite->nodefinido.'"';
        $dataRazones = getDataRazones($tramite, $razones);
        $gradoDenuncia = getGradoDenuncia($max_denuncias, $tramite->cant_registros);
    ?>
    <div class="bloque-tramite<?php echo ($tramite->sello_chilesinpapeleo&&!$mejoras)?' sello_chilesinpapeleo':''; ?><?php echo $tramite->digitalizado?' digitalizado':''; ?><?php echo $canales; ?> <?php echo $gradoDenuncia; ?> <?php echo 't_'.md5($tramite->tramite); ?> <?php echo 'i_'.md5($tramite->institucion); ?> <?php echo 'c_'.$tramite->cant_registros; ?>" data-tramite="<?php echo md5($tramite->tramite); ?>" data-institucion="<?php echo md5($tramite->institucion); ?>" data-cantidad="<?php echo $tramite->cant_registros; ?>"<?php echo $dataCanales; ?> <?php echo $dataRazones; ?> data-url-chileatiende="<?php echo $tramite->url_chileatiende; ?>">
        <div class="filtro-tramite"><?php echo $tramite->tramite; ?><?php echo $tramite->origen==''?' (sic)':''; ?></div>
        <div class="filtro-institucion"><?php echo $tramite->institucion; ?></div>
        <div class="filtro-cantidad"><?php echo $tramite->cant_registros; ?></div>
        <div class="cerrar-tramite">(x)</div>
        <div class="mas-info">
            <strong>Trámite: </strong><?php echo $tramite->tramite; ?><?php echo $tramite->origen==''?' (sic)':''; ?>
        </div>
        <div class="mas-info">
            <strong>Servicio: </strong><?php echo $tramite->institucion; ?>
        </div>
        <div class="mas-info">
            <strong>Cantidad de peticiones: </strong><?php echo $tramite->cant_registros; ?>
        </div>
        <div class="mas-info contenedor-grafico-canales"></div>
        <div class="mas-info contenedor-grafico-razones"></div>
    </div>
<?php } ?>
</div>
<?php echo $pagination; ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("visualization", "1", {packages:["corechart"]});</script>