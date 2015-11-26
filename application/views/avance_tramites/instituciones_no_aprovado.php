<?php foreach ($instituciones as $key => $institucion): ?>
    <div class="span4">
        <div class="cont-institucion">
            <div class="encabezado-institucion">
                <div class="row-fluid">
                    <div class="span12">
                        <a title="<?php echo $institucion->nombre; ?>" href="<?php echo site_url('avance/institucion/'.$institucion->codigo); ?>">
                            <h3 class="nombre">
                                <?php echo strlen($institucion->nombre)>31?mb_substr($institucion->nombre, 0, 31).'...':$institucion->nombre; ?>
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
            <div class="datos-institucion">
                <div class="row-fluid">
                    <div class="span7">
                        <div class="cant-tramites">
                            <span class="verde"><?php echo str_pad($institucion->cant_digitalizados, 2,'0', STR_PAD_LEFT); ?></span><small>/<?php echo str_pad($institucion->cant_tramites, 2, '0', STR_PAD_LEFT); ?></small>
                            <div class="texto-cant-tramites"><span class="verde">Trámites Digitalizados</span> / Trámites Comprometidos</div>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="porc-avance <?php echo $institucion->porc_digitalizados>=50?'verde':'gris-oscuro'; ?>">
                            <div class="cont-pie-chart" data-percent="<?php echo number_format($institucion->porc_digitalizados); ?>" data-forcebarcolor="<?php echo getColorPorcentaje(number_format($institucion->porc_digitalizados)); ?>" <?php echo number_format($institucion->porc_digitalizados)==0?'data-track-color="#c16e65"':''; ?>>
                                <?php echo number_format($institucion->porc_digitalizados); ?>
                            </div>
                            <div class="texto-progreso-digitalizacion verde">% Progreso de Digitalización</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bajada-institucion">
                <div class="row-fluid">
                    <div class="span10">
                        <strong><?php echo $institucion->cant_servicios; ?></strong> Instituciones
                    </div>
                    <div class="span2">
                        <a class="ver-mas" title="<?php echo $institucion->nombre; ?>" href="<?php echo site_url('avance/institucion/'.$institucion->codigo); ?>">
                            ver mas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach ?>