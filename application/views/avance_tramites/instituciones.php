<div class="span6">
    <div class="cont-info-avance">
        <p>
            Estamos trabajando para facilitarte la vida.
            <br>
            A través de este tablero podrás conocer los compromisos de las instituciones en digitalización: cuánto han avanzado, qué trámites podrás realizar próximamente a través de internet y cuales procedimientos ya puedes hacer totalmente en línea, sin salir de tu casa.
            <br>
            Sigue en línea el avance de las instituciones públicas para alcanzar la meta de un 60% de trámites digitalizados al finalizar  este gobierno.
        </p>
        <p class="small">
            La información presentada es la reportada por las instituciones al 5 de Junio del 2014.
        </p>
    </div>
</div>
<div class="span6">
    <div class="cont-listado-porcentaje-instituciones">
        <?php $i = 11; ?>
        <?php foreach ($instituciones_agrupadas as $porcentaje => $instituciones): ?>
            <?php if (count($instituciones)): ?>
                <div class="row-fluid">
                    <div class="span12 span-instituciones-porcentaje">
                        <div class="cont-instituciones-porcentaje porcentaje-<?php echo $i; ?>">
                            <div class="instituciones-porcentaje">
                                <h2><?php echo $porcentaje; ?>%<br>Digitalización Comprometida</h2>
                                <ul class="list-instituciones-porcentaje">
                                    <?php foreach ($instituciones as $institucion): ?>
                                        <li>
                                            <a href="<?php echo site_url('avance/tramo/'.($porcentaje).'/#'.$institucion->codigo); ?>" id="institucion-<?php echo $institucion->codigo; ?>"><?php echo $institucion->nombre; ?></a>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php $i--; ?>
        <?php endforeach ?>
    </div>
</div>
