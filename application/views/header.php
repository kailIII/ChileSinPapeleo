<header class="container header">
    <div class="row">
        <!-- Logo y nombre de campaña -->
        <div class="span8">
            <h1>
                <a href="<?= site_url('') ?>"><img src="<?= site_url('assets/img/logo.png') ?>" alt="logo" id="logo">
                    Chile sin papeleo
                </a>
            </h1>
        </div>
        <div class="span4">
            <div class="row-fluid">
                <div class="span12"><a href="http://www.chileatiende.cl" target="_blank" class="pull-right"><img src="<?= site_url('assets/img/logo_cha.png') ?>" alt="Logo ChileAtiende"></a></div>
            </div>
        </div>
    </div>
    <!-- Menu & Search -->
    <div class="row">
        <nav class="span12">
            <ul class="unstyled main-menu">
                <li<?php echo $active_menu=='portada'?' class="active"':''; ?>>
                    <a href="<?= site_url('') ?>">Portada</a>
                </li>
                <li<?php echo $active_menu=='acerca-de-esta-campana'?' class="active"':''; ?>><a href="<?= site_url('paginas/ver/acerca-de-esta-campana') ?>">Acerca de esta campaña</a></li>
                <li<?php echo $active_menu=='como-participar'?' class="active"':''; ?>><a href="<?= site_url('paginas/ver/como-participar') ?>">Cómo participar</a></li>
                <li<?php echo $active_menu=='conoce-tus-derechos-y-deberes'?' class="active"':''; ?>><a href="<?= site_url('paginas/ver/conoce-tus-derechos-y-deberes') ?>">Conoce tus derechos</a></li>
                <li class="dropdown <?php echo in_array($active_menu, array('sigue-el-avance', 'peticiones-ciudadanas', 'avance'))?' active':''; ?>">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Sigue el avance <i class="icon-flecha-dropdown"></i>
                    </a>
                    <ul class="dropdown-menu">
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
                </li>                       
                <li>
                    <a href="http://instituciones.chilesinpapeleo.cl/">Instituciones</a>
                </li>
            </ul>
        </nav>
    </div>
</header>