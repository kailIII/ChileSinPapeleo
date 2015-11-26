<div class="page-header">
    <h1>Páginas <small>Administra tus páginas</small></h1>
</div>

<a href="<?= site_url('backend/agregar') ?>" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> Agregar página</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($paginas as $pagina) {
            ?>
            <tr>
                <td><?= $pagina->id ?></td>
                <td><a href="<?= site_url('backend/editar/' . $pagina->amigable) ?>"><?= $pagina->titulo ?></a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
