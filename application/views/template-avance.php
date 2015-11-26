<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <!-- Data about Data, so meta -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width" />

        <title>Chile Sin Papeleo - <?= $title ?></title>

        <!-- Zhe fonts und styles -->
        <link href="http://fonts.googleapis.com/css?family=Ubuntu:bold" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?= site_url('assets/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/css/style.css?v6') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/css/chosen.css') ?>">

        <!-- Le Favicon and zhe lame apple stuffz -->
        <link rel="shortcut icon" href="<?= site_url('assets/img/favicon.ico') ?>">
        <link rel="apple-touch-icon-precomposed" href="<?= site_url('assets/img/apple-touch-icon-precomposed.png') ?>">

        <script src="<?= site_url('assets/js/libs/modernizr-2.5.3.min.js') ?>"></script>

        <script type="text/javascript">
            window.____aParams = {"gobabierto":"1","buscadore":"1","domain":"www.chilesinpapeleo.cl","icons":"1"};
            (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.modernizacion.cl/barra/js/barraEstado.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
        </script>
    </head>
    <body>
    <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
    <?php //$this->load->view('header'); ?>

    <header class="container header">
        <div class="row">
            <nav class="span12">
                <ul class="unstyled main-menu">
                    <li<?php echo $active_menu=='avance'?' class="active"':''; ?>>
                        <a href="<?= site_url('avance') ?>">Avances</a>
                    </li>
                    <li<?php echo $active_menu=='sigue-el-avance'?' class="active"':''; ?>>
                        <a href="<?= site_url('paginas/ver/sigue-el-avance') ?>">Trámites digitalizados</a>
                    </li>
                    <li<?php echo $active_menu=='peticiones-ciudadanas'?' class="active"':''; ?>>
                        <a href="<?= site_url('paginas/ver/peticiones-ciudadanas') ?>">Peticiones Ciudadanas</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container container-avance">
        <div class="row">
            <div class="header-avance">
                <div class="span8">
                    <h2>Avances</h2>
                </div>
                <div class="span4">
                    <div class="cont-compartir">
                        <span>Compartir</span>
                        <a class="compartir_twitter" target="_blank" href="http://twitter.com/intent/tweet?text=<?php echo urlencode('Chile Sin Papeleo - '.$title); ?>&url=<?php echo urlencode(current_url()); ?>&via=chilesinpapeleos">Twitter</a>
                        <a class="compartir_facebook" target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo urlencode(current_url()); ?>&t=<?php echo urlencode('Chile Sin Papeleo - '.$title); ?>">Facebook</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span12">
                <hr class="separador-avance">
            </div>
        </div>
        <div class="row">
            <div class="span6">
                <div class="breadcrumbs">
                    <a href="<?php echo site_url(''); ?>">Portada</a> / 
                    <a href="<?php echo site_url('avance'); ?>">Avances</a>
                    <?php if(isset($tramo)): ?>
                        / Porcentaje promedio <?php echo $tramo; ?>%
                    <?php endif ?>
                </div>
            </div>
            <div class="span6">
                <form action="<?php echo site_url('avance'); ?>" class="form-buscar-institucion pull-right">
                    <select class="input-xlarge select-instituciones" data-placeholder="Instituciones" data-toggle="chosen" name="institucion" id="institucion">
                        <option value=""></option>
                        <?php foreach ($instituciones_agrupadas as $porcentaje => $instituciones): ?>
                            <optgroup label="<?php echo $porcentaje; ?>%">
                                <?php foreach ($instituciones as $institucion): ?>
                                    <option value="<?php echo $institucion->codigo; ?>" data-link="<?php echo site_url('avance/tramo/'.$porcentaje.'/#'.$institucion->codigo); ?>"><?php echo preg_replace('/Ministerio ?(del?)?/', 'Min. ', $institucion->nombre); ?></option>
                                <?php endforeach ?>
                            </optgroup>
                        <?php endforeach ?>
                    </select>
                    <button type="submit" class="btn-buscar-institucion">Buscar</button>
                </form>
            </div>
        </div>
        <div class="row">
            <?php echo $content; ?>
        </div>
    </div>
    <input type="hidden" id="site_url" value="<?php echo site_url(); ?>">
    <?php //$this->load->view('footer'); ?>
    <!-- JS & bottom like zhe pro's -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="<?= site_url('assets/js/libs/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?= site_url('assets/js/libs/chosen.jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?= site_url('assets/js/libs/jquery.ba-hashchange.min.js') ?>"></script>
    <script src="<?= site_url('assets/js/plugins.js') ?>"></script>
    <script src="<?= site_url('assets/js/script.js') ?>?v=1"></script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-23675324-11']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

    </script>
</body>
</html>
