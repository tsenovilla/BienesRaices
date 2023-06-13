<main class="container seccion narrow">
    <h1>
        <?php 
            switch($lang):
                case "es":
                    echo $entrada->get_titulo_espanol();
                    break;
                case "en":
                    echo $entrada->get_titulo_english();
                    break;
                endswitch;
        ?>
    </h1>
    <div class="entrada-blog__texto">
        <p><?php echo ["en"=>"Written on", "es"=>"Escrito el"][$lang];?> <span><?php echo $entrada->get_fecha();?></span> <?php echo ["en"=>"by","es"=>"por"][$lang];?> <span><?php echo $entrada->get_autor();?></span></p>
        <?php
            if(is_file(DEPLOYED_IMAGES_URL.$entrada->get_imagen())): 
        ?>
            <div>
                <img loading="lazy" src="/deployed_images/<?php echo $entrada->get_imagen();?>" alt="Imagen blog">
            </div>
        <?php
            endif;
        ?>
        <p>
        <?php 
            switch($lang):
                case "es":
                    echo $entrada->get_texto_espanol();
                    break;
                case "en":
                    echo $entrada->get_texto_english();
                    break;
                endswitch;
        ?>
        </p>
    </div>
</main>