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
        <link rel="stylesheet" href="<?= site_url('assets/css/style.css?v1') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/css/sigue_el_avance.css') ?>">

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

<header class="container header">
    <div class="row">
        <nav class="span12">
            <ul class="unstyled main-menu">
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

    <?php //echo $this->load->view('header'); ?>
    <article class="container">
        <?= $photobooth ?>
        <?= $stuff ?>
    </article>
    <?php //echo $this->load->view('footer'); ?>



    <!-- JS & bottom like zhe pro's -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="<?= site_url('assets/js/libs/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('assets/js/libs/jquery.isotope.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= site_url('assets/js/jquery.Rut.min.js') ?>"></script>
    <script src="<?= site_url('assets/js/plugins.js') ?>"></script>
    <script src="<?= site_url('assets/js/script.js') ?>"></script>

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
