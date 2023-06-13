<section>
    <?php
        foreach($entradas as $entrada):
    ?>
    <article class="entrada-blog <?php echo $entrada->get_imagen() ? "includes-image" : "center-align";?>">
        <?php
            if(is_file(DEPLOYED_IMAGES_URL.$entrada->get_imagen())): 
        ?>
        <div>
            <img loading="lazy" src="/deployed_images/<?php echo $entrada->get_imagen();?>" alt="Imagen blog">
        </div>
        <?php
            endif;
        ?>
        <div class="entrada-blog__texto">
            <a href="<?php echo "/$lang";?>/entrada?id=<?php echo $entrada->get_id();?>">
                <h4>
                    <?php 
                        switch ($lang):
                            case "en":
                                echo $entrada->get_titulo_english();
                                break;
                            case "es":
                                echo $entrada->get_titulo_espanol();
                                break;
                        endswitch;
                    ?>
                </h4>
                <p><?php echo ["en"=>"Written on", "es"=>"Escrito el"][$lang];?> <span><?php echo $entrada->get_fecha();?></span> <?php echo ["en"=>"by","es"=>"por"][$lang];?> <span><?php echo $entrada->get_autor();?></span></p>
                <p>
                    <?php 
                        switch ($lang):
                            case "en":
                                echo $entrada->get_resumen_english();
                                break;
                            case "es":
                                echo $entrada->get_resumen_espanol();
                                break;
                        endswitch;
                    ?>
                </p>
            </a>
        </div>
    </article>
    <?php
        endforeach;
    ?>
    <?php 
        if(!$entradas):
    ?>
            <h2 class="text-center"><?php echo ["en"=>"There's not any entry at this time","es"=>"No hay entradas en este momento"][$lang];?></h2>
    <?php 
        endif;
    ?>
</section>
