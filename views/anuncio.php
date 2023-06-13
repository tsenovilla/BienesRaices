<main class="container seccion narrow">
    <h1>
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
    </h1>
    <div class="anuncio">
        <img loading="lazy" src="/deployed_images/<?php echo $anuncio->get_imagen();?>" alt="Imagen anuncio">
        <div class="contenido-anuncio">
            <p class="contenido-anuncio__publicacion"><?php echo ["en"=>"Available since:","es"=>"Disponible desde:"][$lang];?> <span><?php echo $anuncio->get_fecha_publicacion();?><span></p>
            <p class="contenido-anuncio__precio">$<?php echo $anuncio->get_precio();?></p>
            <ul class="contenido-anuncio__iconos">
                <li>
                    <img loading="lazy" src="/build/img/icono_dormitorio.svg" alt="Icono dormitorio">
                    <p><?php echo $anuncio->get_dormitorios()?></p>
                </li>
                <li>
                    <img loading="lazy" src="/build/img/icono_wc.svg" alt="Icono WC">
                    <p><?php echo $anuncio->get_wc();?></p>
                </li>
                <li>
                    <img loading="lazy" src="/build/img/icono_estacionamiento.svg" alt="Icono estacionamiento">
                    <p><?php echo $anuncio->get_aparcamientos()?></p>
                </li>
            </ul>
            <p>
                <?php
                    switch($lang):
                        case "en":
                            echo $anuncio->get_descripcion_english();
                            break;
                        case "es":
                            echo $anuncio->get_descripcion_espanol();
                            break;
                    endswitch;
                ?>
            </p>
            <div class="contenido-anuncio__vendedor">
                <p class="contenido-anuncio__vendedor--encabezado"> <?php echo ["en"=>"Seller","es"=>"Vendedor"][$lang];?>: </p>
                <p class="contenido-anuncio__vendedor--nombre"><?php echo $vendedor->get_nombre()." ".$vendedor->get_apellido();?></p>
            </div>
        </div><!--.contenido-anuncio-->
    </div><!--.anuncio-->
</main>