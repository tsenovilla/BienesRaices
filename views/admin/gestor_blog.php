<main class="container seccion narrow">
    <div class="right-align">
        <a href="<?php echo "/$lang";?>/admin/blog" class="adminback-internal"><?php echo ["en"=>"Back","es"=>"Regresar"][$lang];?></a>
    </div>
    <h1><?php echo $page;?></h1>
    <?php
    echo $KO_alert;
    ?>
    <p class="text-center small-font-size"><?php echo ["en"=>"Felds marked with * are mandatory","es"=>"Los campos marcados con * son obligatorios"][$lang];?></p>
    <form class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <label for="titulo_espanol">*<?php echo ["en"=>"Entry title (Spanish)","es"=>"Título de la entrada(Español)"][$lang];?>:</label>
            <input 
                type="text" 
                id="titulo_espanol" 
                name="posted[titulo_espanol]" 
                placeholder="<?php echo ["en"=>"Entry title in Spanish","es"=>"Título de la entrada en español"][$lang];?>"
                class="<?php echo $error==1 ? "input_error" : "";?>" 
                value="<?php echo $entrada->get_titulo_espanol();?>"
            >
            <label for="titulo_english">*<?php echo ["en"=>"Entry title (English)","es"=>"Título de la entrada(Inglés)"][$lang];?>:</label>
            <input 
                type="text" 
                id="titulo_english" 
                name="posted[titulo_english]" 
                placeholder="<?php echo ["en"=>"Entry title in English","es"=>"Título de la entrada en inglés"][$lang];?>"
                class="<?php echo $error==2 ? "input_error" : "";?>" 
                value="<?php echo $entrada->get_titulo_english();?>"
            >
            <label for="autor">*<?php echo ["en"=>"Author","es"=>"Autor"][$lang]?>:</label>
            <input 
                type="text"
                id="autor" 
                name="posted[autor]" 
                placeholder="<?php echo ["en"=>"Author's name","es"=>"Nombre del autor"][$lang];?>"
                class="<?php echo $error==3 ? "input_error" : "";?>"
                value="<?php echo $entrada->get_autor()?>"
            >
            <label for="resumen_espanol"><?php echo ["en"=>"Entry summary (Spanish)","es"=>"Resumen de la entrada(Español)"][$lang];?>:</label>
            <textarea id="resumen_espanol" name="posted[resumen_espanol]" class="resumen <?php echo $error==4 ? "input_error" : "";?>"><?php echo $entrada->get_resumen_espanol();?></textarea>
            <label for="resumen_english"><?php echo ["en"=>"Entry summary (English)","es"=>"Resumen de la entrada(Inglés)"][$lang];?>:</label>
            <textarea id="resumen_english" name="posted[resumen_english]" class="resumen <?php echo $error==5 ? "input_error" : "";?>"><?php echo $entrada->get_resumen_english();?></textarea>
            <label for="imagen">*<?php echo ["en"=>"Image","es"=>"Imagen"][$lang];?>:</label>
            <input 
                type="file" 
                id="imagen" 
                accept="image/jpeg,image/png,image/webp"
                name="imagen"
            >
            <?php
                if(is_file(DEPLOYED_IMAGES_URL.$entrada->get_imagen())):                     
            ?>
                <img src="/deployed_images/<?php echo $entrada->get_imagen();?>" alt="Imagen de entrada" class="formulario__imagen">
            <?php
                endif;
            ?>
            <label for="texto_espanol"><?php echo ["en"=>"Entry body (Spanish)","es"=>"Cuerpo de la entrada(Español)"][$lang];?>:</label>
            <textarea id="texto_espanol" name="posted[texto_espanol]" class="<?php echo $error==6 ? "input_error" : "";?>"><?php echo $entrada->get_texto_espanol();?></textarea>
            <label for="texto_english"><?php echo ["en"=>"Entry body (English)","es"=>"Cuerpo de la entrada(Inglés)"][$lang];?>:</label>
            <textarea id="texto_english" name="posted[texto_english]" class="<?php echo $error==7 ? "input_error" : "";?>"><?php echo $entrada->get_texto_english();?></textarea>
        </fieldset>
        <input type="submit" value="<?php echo $boton_text;?>" class="formulario__boton">
    </form>
</main>