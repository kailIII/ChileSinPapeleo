<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Chile sin Papeleo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link rel="stylesheet" href="<?= site_url('assets/css/bootstrap.min.css') ?>">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }
        </style>
        <link href="<?= site_url('assets/css/bootstrap-responsive.css') ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo site_url('assets/css/backend.css'); ?>">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="../assets/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="stylesheet" href="<?= site_url('assets/css/redactor/redactor.css') ?>" />
        <script src="<?= site_url('assets/js/jquery-1.8.0.min.js') ?>"></script>
        <script src="<?= site_url('assets/js/redactor/redactor.min.js') ?>"></script>
    </head>

    <body>

        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Chile sin Papeleo</a>
                    <div class="btn-group pull-right">
                        <!--
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-user"></i> Username
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Sign Out</a></li>
                        </ul>
                        -->
                    </div>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active"><a href="<?= site_url('backend/paginas/') ?>">Home</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <li class="nav-header">Páginas</li>
                            <li <?php echo $this->uri->segment(2)=='paginas'?'class="active"':''; ?>><a href="<?= site_url('backend/paginas/') ?>">Listar</a></li>
                            <li class="nav-header">Denuncias</li>
                            <li <?php echo ($this->uri->segment(2)=='resultados' && $this->uri->segment(3)!='mejoras')?'class="active"':''; ?>><a href="<?php echo site_url('backend/resultados'); ?>">Listar</a></li>
                            <li <?php echo $this->uri->segment(3)=='mejoras'?'class="active"':''; ?>><a href="<?php echo site_url('backend/resultados/mejoras'); ?>">Listar Mejoras</a></li>
                            <li class="nav-header">Trámites</li>
                            <li <?php echo $this->uri->segment(2)=='tramites'?'class="active"':''; ?>><a href="<?php echo site_url('backend/tramites'); ?>">Listar</a></li>
                        </ul>
                    </div><!--/.well -->
                </div><!--/span-->
                <div class="span10">
                    <?php
                    $this->load->view($vista);
                    ?>
                </div><!--/span-->
            </div><!--/row-->

            <hr>

            <footer>
                <p>Copyleft Modernización y Gobierno Digital 2012</p>
            </footer>

        </div><!--/.fluid-container-->

        <script type="text/javascript" src="<?php echo site_url('assets/js/bootstrap-modal.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_url('assets/js/backend.js'); ?>"></script>
    </body>
</html>


