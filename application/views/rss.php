<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
    <channel>
        <title>Trámites más votados</title>
        <link><?php echo site_url(); ?></link>
        <description>Listado de los trámites más votados en el último mes para su digitalización.</description>
        <?php foreach ($tramites as $tramite): ?>
            <item>
                <title><?php echo $tramite->tramite; ?></title>
                <link><?php echo $tramite->url_chileatiende; ?></link>
                <author><?php echo $tramite->institucion; ?></author>
                <description><?php echo $tramite->cant_registros; ?></description>
            </item>
        <?php endforeach ?>
    </channel>
</rss>