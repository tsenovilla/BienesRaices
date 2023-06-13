<main class="container seccion">
    <h1>Administrador</h1>
    <?php // Este bloque PHP muestra las alertas de correcto funcionamiento o error con respecto a acciones de la base de datos
        echo $OK_alert;
        echo $KO_alert;
    ?>
    <section class="admin-menu">
        <a href="<?php echo "/$lang";?>/admin/anuncio/crear" class="admin-menu__enlace"><?php echo ["en"=>"Create a new ad","es"=>"Crear un nuevo anuncio"][$lang];?></a>
        <a href="<?php echo "/$lang";?>/admin/vendedores" class="admin-menu__enlace"><?php echo ["en"=>"Manage sellers","es"=>"Gestionar vendedores"][$lang];?></a>
        <a href="<?php echo "/$lang";?>/admin/solicitudes" class="admin-menu__enlace"><?php echo ["en"=>"Manage contact requests", "es"=>"Gestionar solicitudes de contacto"][$lang];?></a>
        <a href="<?php echo "/$lang";?>/admin/blog" class="admin-menu__enlace"><?php echo ["en"=>"Manage blog entries", "es"=>"Gestionar entradas del blog"][$lang];?></a>
        <div class="admin-menu__vendedores">
                <p><?php echo ["en"=>"Show ads by", "es"=>"Mostrar anuncios de"][$lang]?>: </p>
                <a class="admin-menu__vendedores--vendedor admin-menu__vendedores--selected"><?php echo $id_vendedor ? $vendedor_activo->get_nombre()." ".$vendedor_activo->get_apellido() : ["en"=>"All sellers","es"=>"Todos los vendedores"][$lang];?></a>
                <div class="admin-menu__vendedores--dropdown display-none">
                    <?php
                        if($id_vendedor): // Si hay vendedor seleccionado, ponemos la opción de todos los vendedores como primera del desplegable
                    ?>
                            <a href="<?php echo "/$lang";?>/admin" class="admin-menu__vendedores--vendedor"><?php echo ["en"=>"All sellers","es"=>"Todos los vendedores"][$lang];?></a>
                    <?php 
                        endif;
                        foreach($vendedores as $vendedor):
                            if($vendedor->get_id()==$id_vendedor) continue;
                    ?>
                            <a 
                                href="<?php echo "/$lang";?>/admin?id_vendedor=<?php echo $vendedor->get_id();?>" class="admin-menu__vendedores--vendedor"
                            >
                                <?php echo $vendedor->get_nombre()." ".$vendedor->get_apellido();?>
                            </a>                    
                    <?php
                        endforeach;
                    ?>
                </div>
        </div>
    </section>
    <section class="admin-list">
        <div class="admin-list__content">
            <p class="text-center admin-list__header"><?php echo ["en"=>"Title","es"=>"Título"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Image","es"=>"Imagen"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Price","es"=>"Precio"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Actions","es"=>"Acciones"][$lang];?></p>
        </div>
        <?php
            foreach($anuncios as $anuncio):
        ?>
                <div class="admin-list__content">
                    <div class="center-align">
                        <p>
                            <?php
                                switch($lang):
                                    case "en":
                                        echo $anuncio->get_titulo_english();
                                        break;
                                    case "es":
                                        echo $anuncio->get_titulo_espanol();
                                        break;
                                endswitch;
                            ?>
                        </p>
                    </div>
                    <img src="/deployed_images/<?php echo $anuncio->get_imagen();?>" alt="Imagen de propiedad">
                    <div class="center-align">
                        <p>$<?php echo $anuncio->get_precio();?></p>
                    </div>
                    <div class="admin-list__acciones">
                        <a href="<?php echo "/$lang";?>/admin/anuncio/actualizar?id=<?php echo $anuncio->get_id();?>" class="admin-list__acciones--accion width_100"><?php echo ["en"=>"Update","es"=>"Actualizar"][$lang];?></a>
                        <a href="<?php echo "/$lang";?>/admin/anuncio/borrar?id=<?php echo $anuncio->get_id();?>" class="admin-list__acciones--accion width_100"><?php echo ["en"=>"Delete","es"=>"Eliminar"][$lang];?></a>
                    </div>
                </div><!--.admin-list__content-->
        <?php 
            endforeach;
        ?>
        <?php 
            if(!$anuncios):
        ?>
            <h2 class="text-center"><?php echo ["en"=>"There's not any ad published by the selected seller at this time","es"=>"No hay anuncios publicados por este vendedor en este momento"][$lang];?></h2>
        <?php 
            endif;
        ?>
    </section>
</main>