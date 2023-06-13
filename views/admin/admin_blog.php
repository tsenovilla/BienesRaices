<main class="container seccion admin-blog">
    <?php
        include __DIR__."/../templates/admin_back.php";
    ?>
    <h1><?php echo ["en"=>"Manage blog entries","es"=>"Administrar las entradas del blog"][$lang];?></h1>
    <?php 
        echo $OK_alert;
        echo $KO_alert;
    ?>
    <section class="admin-menu">
        <a href="<?php echo "/$lang";?>/admin/blog/crear" class="admin-menu__enlace"><?php echo ["en"=>"Add a new entry", "es"=>"Añadir nueva entrada"][$lang];?></a>
    </section>
    <section class="admin-list">
        <div class="admin-list__content">
            <p class="text-center admin-list__header"><?php echo ["en"=>"Title","es"=>"Título"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Author","es"=>"Autor"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Summary","es"=>"Resumen"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Actions","es"=>"Acciones"][$lang];?></p>
        </div>
        <?php
            foreach($entradas as $entrada):
        ?>
                <div class="admin-list__content">
                    <div class="center-align">
                        <p>
                            <?php 
                                switch($lang):
                                    case "en":
                                        echo $entrada->get_titulo_english();
                                        break;
                                    case "es":
                                        echo $entrada->get_titulo_espanol();
                                        break;
                                endswitch;
                            ?>
                        </p>
                    </div>
                    <div class="center-align">
                        <p><?php echo $entrada->get_autor();?></p>
                    </div>
                    <div class="center-align">
                        <p>
                            <?php 
                                switch($lang):
                                    case "en":
                                        echo $entrada->get_resumen_english();
                                        break;
                                    case "es":
                                        echo $entrada->get_resumen_espanol();
                                        break;
                                endswitch;
                            ?>
                        </p>
                    </div>
                    <div class="admin-list__acciones">
                        <a href="<?php echo "/$lang";?>/admin/blog/actualizar?id=<?php echo $entrada->get_id();?>" class="admin-list__acciones--accion width_100"><?php echo ["en"=>"Update","es"=>"Actualizar"][$lang];?></a>
                        <a href="<?php echo "/$lang";?>/admin/blog/borrar?id=<?php echo $entrada->get_id();?>" class="admin-list__acciones--accion width_100"><?php echo ["en"=>"Delete","es"=>"Eliminar"][$lang];?></a>
                    </div>
                </div><!--.admin-list__content-->
        <?php 
            endforeach;
        ?>
        <?php 
            if(!$entradas):
        ?>
                <h2 class="text-center"><?php echo ["en"=>"There's no entries at this time","es"=>"No hay entradas en este momento"][$lang];?></h2>
        <?php 
            endif;
        ?>
    </section>
</main>