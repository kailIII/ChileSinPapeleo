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
<div class="cont-resultados">
    <div class="cont-resultado cont-tramites-digitalizados">
        <div class="cont-cant-tramites">
            <div class="cant-tramites-digitalizados">
                <span><?php echo $total_digitalizados; ?></span>
            </div>
        </div>
        <h3 class="<?php echo (!$peticiones && !$proactivas)?'active':''; ?>">trámites digitalizados</h3>
        <a href="<?php echo current_url(); ?>" class="btn btn-primary">ver todos</a>
    </div>
    <img src="<?php echo base_url('assets/img/sigue_el_avance/signo_igual.png'); ?>" class="signo_igual">
    <div class="cont-resultado cont-tramites-votados">
        <div class="cont-cant-tramites">
            <div class="cant-tramites-votados">
                <span><?php echo $total_con_peticiones; ?></span>
            </div>
        </div>
        <h3 class="<?php echo ($peticiones)?'active':''; ?>">en respuesta a <?php echo $total_peticiones_digitalizadas; ?> peticiones</h3>
        <a href="<?php echo current_url(); ?>?peticiones=1" class="btn btn-primary">ver detalle</a>
    </div>
    <img src="<?php echo base_url('assets/img/sigue_el_avance/signo_mas.png'); ?>" class="signo_mas">
    <div class="cont-resultado cont-tramites-proactivos">
        <div class="cont-cant-tramites">
            <div class="cant-tramites-proactivos">
                <span><?php echo $total_digitalizados-$total_con_peticiones; ?></span>
            </div>
        </div>
        <h3 class="<?php echo ($proactivas)?'active':''; ?>">digitalizados proactivamente</h3>
        <a href="<?php echo current_url(); ?>?proactivas=1" class="btn btn-primary">ver detalle</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="contenedor-filtros-tramites row-fluid filtros-tramites-digitalizados">
    <div class="span4">
        <div class="titulo-filtros">
            <h3>Filtros</h3>
            <p>Para resultados más específicos</p>
        </div>
    </div>
    <div class="span8">
        <form action="<?php echo current_url(); ?>" method="GET" id="formSigueElAvance">
            <div class="control-group">
                <div class="controls">
                    <select name="instituciones" id="instituciones">
                        <option value="">- Instituciones -</option>
                        <?php foreach ($instituciones as $key => $institucion){ ?>
                            <option <?php echo $selectedInstitucion==md5($institucion->institucion)?'selected="selected"':''; ?> value="<?php echo md5($institucion->institucion); ?>"><?php echo $institucion->institucion; ?> (<?php echo $institucion->cant_registros; ?>)</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label>Ordenar por:</label>
                <div class="btn-group" data-toggle="buttons-radio">
                    <button type="button" class="btn btn-small btn-filtro <?php echo $orderby=='cant_registros'?'active':''; ?>" data-filtro="cant_registros" data-direccion="<?php echo $orderdir=="DESC"; ?>">Cantidad <?php echo $orderby=='cant_registros'?$icon:''; ?></button>
                    <button type="button" class="btn btn-small btn-filtro <?php echo $orderby=='institucion'?'active':''; ?>" data-filtro="institucion" data-direccion="<?php echo $orderdir=="DESC"; ?>">Institución <?php echo $orderby=='institucion'?$icon:''; ?></button>
                    <button type="button" class="btn btn-small btn-filtro <?php echo $orderby=='tramite'?'active':''; ?>" data-filtro="tramite" data-direccion="<?php echo $orderdir=="DESC"; ?>">Trámite <?php echo $orderby=='tramite'?$icon:''; ?></button>
                </div>
            </div>
            <input type="hidden" id="orderby" name="orderby" value="<?php echo htmlentities($orderby); ?>">
            <input type="hidden" id="orderdir" name="orderdir" value="<?php echo htmlentities($orderdir); ?>">
            <input type="hidden" id="offset" name="offset" value="<?php echo htmlentities($offset); ?>">
        </form>
    </div>
</div>
<?php echo $pagination; ?>
<div class="row-fluid">
    <div class="pull-right">
        <a class="descarga-tramites" target="_blank" href="<?php echo base_url('assets/docs/lista_tramites_chilesinpapeleo.xlsx'); ?>"><i class="icon-download"></i> Descargar listado de trámites.</a>
    </div>
</div>
<br>
<div id="contenedor-tramites" class="tramites-digitalizados">
<?php foreach ($tramites as $key => $tramite){ ?>
    <?php
        $canales = ($tramite->oficina>0?' oficina':'').($tramite->online?' online':'').($tramite->correo?' correo':'').($tramite->telefono?' telefono':'').($tramite->nodefinido?' sin-definir':'');
        $dataCanales = ' data-oficina="'.$tramite->oficina.'" data-online="'.$tramite->online.'" data-correo="'.$tramite->correo.'" data-telefono="'.$tramite->telefono.'" data-nodefinido="'.$tramite->nodefinido.'"';
        $dataRazones = getDataRazones($tramite, $razones);
        $gradoDenuncia = getGradoDenuncia($max_denuncias, $tramite->cant_registros);
    ?>
    <div class="bloque-tramite<?php echo $tramite->digitalizacion_proactiva?' proactivo':''; ?> <?php echo $tramite->url_chileatiende?'tiene-url':''; ?> <?php echo $canales; ?> <?php echo $gradoDenuncia; ?> <?php echo 't_'.md5($tramite->tramite); ?> <?php echo 'i_'.md5($tramite->institucion); ?> <?php echo 'c_'.$tramite->cant_registros; ?>" data-tramite="<?php echo md5($tramite->tramite); ?>" data-institucion="<?php echo md5($tramite->institucion); ?>" data-cantidad="<?php echo $tramite->cant_registros; ?>"<?php echo $dataCanales; ?> <?php echo $dataRazones; ?> data-url-chileatiende="<?php echo $tramite->url_chileatiende; ?>">
        <div class="filtro-tramite"><?php echo $tramite->tramite; ?></div>
        <div class="filtro-institucion"><?php echo $tramite->institucion; ?></div>
        <?php /*
        <div class="filtro-cantidad label label-success"><?php echo $tramite->cant_registros; ?></div>
        */ ?>
        <div class="cerrar-tramite">(x)</div>
        <?php if ($tramite->url_chileatiende): ?>
            <div class="mas-info cont-url-chileatiende">
                <a href="<?php echo $tramite->url_chileatiende; ?>" target="_blank" class="btn btn-small btn-success">
                    Ver trámite en ChileAtiende
                </a>
            </div>
        <?php endif ?>
        <div class="mas-info">
            <?php echo $tramite->institucion; ?>
            <?php /*
            <span class="label label-success"><?php echo $tramite->cant_registros; ?></span>
            */ ?>
        </div>
    </div>
<?php } ?>
</div>
<?php echo $pagination; ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("visualization", "1", {packages:["corechart"]});</script>