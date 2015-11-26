<div class="span12">
    <div class="encabezado-detalle-institucion">
        <div class="row-fluid">
            <div class="span5">
                <div class="row-fluid no-padding no-margin">
                    <div class="span4">
                        <div class="tramites-digitalizados verde">
                            <div class="cantidad verde">
                                <i class="icon-documento verde"></i>
                                <?php echo str_pad($institucion->cant_digitalizados, 2, '0', STR_PAD_LEFT); ?>
                            </div>
                            <div class="small">Trámites Digitalizados</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="tramites-comprometidos">
                            <div class="cantidad">
                                <i class="icon-documento"></i>
                                <?php echo str_pad($institucion->cant_tramites, 2, '0', STR_PAD_LEFT); ?>
                            </div>
                            <div class="small">Trámites Comprometidos</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="porc-avance-cumplido <?php echo $institucion->porc_digitalizados>=50?'verde':'gris-oscuro'; ?>">
                            <div class="cont-pie-chart" data-percent="<?php echo number_format($institucion->porc_digitalizados); ?>" data-forcebarcolor="<?php echo getColorPorcentaje(number_format($institucion->porc_digitalizados)); ?>">
                                <?php echo number_format($institucion->porc_digitalizados); ?>
                            </div>
                            <div class="small verde">% Progreso de Digitalización</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span7">
                <h2>
                    <?php echo $institucion->nombre; ?>
                </h2>
                <small class="gris"><strong><?php echo str_pad($institucion->cant_instituciones, 2, '0', STR_PAD_LEFT); ?></strong> Instituciones</small>
            </div>
        </div>
    </div>
</div>
<div class="span12">
    <div class="row-fluid">
        <div class="tabla-servicios span12" data-active-heading="">
            <?php foreach ($servicios as $key => $servicio): ?>
                <div class="table-servicios-heading" data-heading-id="<?php echo $servicio->codigo; ?>">
                    <div class="row-fluid">
                        <div class="span3">
                            <div class="dato-servicio tramites-digitalizados verde">
                                <div>
                                    <i class="icon-documento verde"></i>
                                    <?php echo number_format($servicio->cant_digitalizados); ?>
                                </div>
                            </div>
                            <div class="dato-servicio tramites-comprometidos gris">
                                <div>
                                    <i class="icon-documento"></i>
                                    <?php echo number_format($servicio->cant_tramites); ?>
                                </div>
                            </div>
                            <div class="dato-servicio porcentaje-cumplido gris">
                                <div>
                                    <div class="cont-pie-chart" data-percent="<?php echo number_format($servicio->porc_digitalizados); ?>" data-size="20" data-line-width="4">
                                        &nbsp;
                                    </div>
                                    <?php echo number_format($servicio->porc_digitalizados); ?>%
                                </div>
                            </div>
                        </div>
                        <div class="span9 nombre-servicio" align="left">
                            <div>
                                <?php echo $servicio->nombre; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-servicios-body">
                    <?php foreach ($servicio->tramites as $key => $tramite): ?>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="tramite-servicio">
                                    <div class="row-fluid">
                                        <div class="span3">
                                            <div class="porcentaje-cumplido">
                                                <div class="row-fluid porcentaje-cumplido-graficos">
                                                    <div class="span6<?php echo $tramite->cumplido<$tramite->comprometido?' warning':''; ?>">
                                                        <?php if ($tramite->cumplido == 100): ?>
                                                            <i class="icon-sello-chilesinpapeleo"></i>
                                                            <?php echo number_format($tramite->cumplido); ?>%
                                                        <?php else: ?>
                                                            <div class="cont-pie-chart" data-percent="<?php echo number_format($tramite->cumplido); ?>" data-size="20" data-line-width="4" data-percent-warning="<?php echo number_format($tramite->comprometido); ?>">
                                                                &nbsp;
                                                            </div>
                                                            <span><?php echo number_format($tramite->cumplido); ?>% </span>
                                                        <?php endif ?>
                                                    </div>
                                                    <div class="span6 gris">
                                                        <div class="cont-pie-chart" data-percent="<?php echo number_format($tramite->comprometido); ?>" data-size="20" data-line-width="4" data-forcebarcolor="#a4a4a4">
                                                            &nbsp;
                                                        </div>
                                                        <span><?php echo number_format($tramite->comprometido); ?>% </span>
                                                    </div>
                                                </div>
                                                <div class="row-fluid">
                                                    <div class="span6">
                                                        <small>Avance</small>
                                                    </div>
                                                    <div class="span6">
                                                        <small>Comprometido</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span9">
                                            <div class="row-fluid nombre-tramite">
                                                <div class="span12">
                                                    <?php echo $tramite->nombre; ?>
                                                </div>
                                            </div>
                                            <div class="row-fluid fechas-tramite">
                                                <div class="span5">
                                                    <strong>Fecha de Inicio: </strong><?php echo strftime('%d %B %Y',strtotime($tramite->fecha_inicio)); ?>
                                                </div>
                                                <div class="span7">
                                                    <strong>Fecha de Término: </strong><?php echo strftime('%d %B %Y',strtotime($tramite->fecha_fin)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>