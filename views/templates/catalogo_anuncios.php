<section class="contenedor-anuncios">
    <?php
        foreach($anuncios as $anuncio):
    ?>
    <div class="anuncio">
        <img loading="lazy" src="/deployed_images/<?php echo $anuncio->get_imagen();?>" alt="Imagen anuncio">
        <div class="contenido-anuncio">
            <h3 class="contenido-anuncio__titulo">
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
            </h3>
            <p class="contenido-anuncio__publicacion"><?php echo ["en"=>"Available since:","es"=>"Disponible desde: "][$lang];?> <span><?php echo $anuncio->get_fecha_publicacion();?><span></p>
            <p class="contenido-anuncio__descripcion">
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
            <p class="contenido-anuncio__precio">$<?php echo $anuncio->get_precio();?></p>
            <ul class="contenido-anuncio__iconos">
                <li>
                    <img loading="lazy" src="/build/img/icono_dormitorio.svg" alt="Icono dormitorio">
                    <p><?php echo $anuncio->get_dormitorios();?></p>
                </li>
                <li>
                    <img loading="lazy" src="/build/img/icono_wc.svg" alt="Icono WC">
                    <p><?php echo $anuncio->get_wc();?></p>
                </li>
                <li>
                    <img loading="lazy" src="/build/img/icono_estacionamiento.svg" alt="Icono estacionamiento">
                    <p><?php echo $anuncio->get_aparcamientos();?></p>
                </li>
            </ul>
            <a href="<?php echo "/$lang";?>/anuncio?id=<?php echo $anuncio->get_id();?>" class="contenido-anuncio__boton"><?php echo ["en"=>"Go to the ad", "es"=>"Ver el anuncio"][$lang];?></a>
        </div><!--.contenido-anuncio-->
    </div><!--.anuncio-->
    <?php
        endforeach;
    ?>
</section>
<?php 
    if(!$anuncios):
?>
        <h2 class="text-center"><?php echo ["en"=>"There's not any ad at this time","es"=>"No hay anuncios en este momento"][$lang];?></h2>
<?php 
    endif;
?>