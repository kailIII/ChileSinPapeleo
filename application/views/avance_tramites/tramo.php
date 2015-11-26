<div class="span12">
    <div class="row">
        <div class="span12">
            <ul class="lista-tramos">
                <?php $i = 11; ?>
                <?php foreach ($options['rangos'] as $porcentaje => $tramos): ?>
                    <?php $active = intval($tramo) == $porcentaje; ?>
                    <li class="porcentaje-<?php echo $i; ?> <?php echo $active ? 'activo':''; ?>">
                        <a href="<?php echo site_url('avance/tramo/'.($porcentaje)); ?>"><?php echo $porcentaje; ?>%</a>
                        <?php if ($active): ?>
                            <div><strong><?php echo $total_tramites; ?></strong><br>Trámites</div>
                        <?php endif ?>
                    </li>
                    <?php $i--; ?>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <?php foreach ($instituciones as $key => $institucion): ?>
        <div class="cont-institucion">
            <div class="row">
                <div class="span12">
                    <div class="encabezado-institucion" id="<?php echo $institucion->codigo; ?>">
                        <strong class="institucion-nombre"><?php echo $key+1; ?>. <?php echo $institucion->nombre; ?></strong>
                        <strong>(<?php echo count($institucion->tramites); ?> trámites)</strong>
                        <span>con un</span>
                        <strong class="verde"><?php echo number_format($institucion->cumplido); ?>%</strong>
                        <?php if ($institucion->cumplido == 100): ?>
                            de cumplimiento
                        <?php else: ?>
                            <span>de avance promedio</span>
                        <?php endif ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="span12">
                    <div class="tramites-institucion">
                        <table class="table table-striped">
                            <?php foreach ($institucion->tramites as $tramite): ?>
                                <tr>
                                    <td class="tramite-porcentaje<?php echo $tramite->cumplido==100?' digitalizado':''; ?>"><?php echo $tramite->cumplido; ?>%</td>
                                    <td class="tramite-info">
                                        <?php if ($tramite->url_cha): ?>
                                        <div class="tramite-nombre">
                                            <a href="<?php echo $tramite->url_cha; ?>" target="_blank"><?php echo $tramite->nombre; ?></a>
                                        </div>
                                        <?php else: ?>
                                        <div class="tramite-nombre"><?php echo $tramite->nombre; ?></div>
                                        <?php endif ?>                                        
                                        <div class="tramite-fechas">
                                            <span class="fecha-inicio"><strong>Fecha de inicio:</strong> <?php echo ucwords(strftime('%d %B %Y', strtotime($tramite->fecha_inicio))); ?></span>
                                            <span class="fecha-termino"><strong>Fecha de término:</strong> <?php echo ucwords(strftime('%d %B %Y', strtotime($tramite->fecha_fin))); ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>