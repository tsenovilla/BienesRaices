<main class="container seccion narrow">
    <?php
        include __DIR__."/../templates/admin_back.php";
    ?>
    <h1><?php echo $page;?></h1>
    <?php
    echo $KO_alert;
    ?>
    <p class="text-center small-font-size"><?php echo ["en"=>"Felds marked with * are mandatory","es"=>"Los campos marcados con * son obligatorios"][$lang];?></p>
    <form class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend><?php echo ["en"=>"General info", "es"=>"Información general"][$lang];?></legend>
            <label for="titulo_espanol">*<?php echo ["en"=>"Title (Spanish)","es"=>"Título (Español)"][$lang];?>:</label>
            <input 
                type="text" 
                id="titulo_espanol" 
                name="posted[titulo_espanol]" 
                placeholder="<?php echo ["en"=>"Title of the ad in Spanish","es"=>"Título del anuncio en español"][$lang];?>"
                class="<?php echo $error==1 ? "input_error" : "";?>" 
                value="<?php echo $propiedad->get_titulo_espanol();?>"
            >
            <label for="titulo_english">*<?php echo ["en"=>"Title (English)","es"=>"Título (Inglés)"][$lang];?>:</label>
            <input 
                type="text" 
                id="titulo_english" 
                name="posted[titulo_english]" 
                placeholder="<?php echo ["en"=>"Title of the ad in English","es"=>"Título del anuncio en inglés"][$lang];?>"
                class="<?php echo $error==2 ? "input_error" : "";?>" 
                value="<?php echo $propiedad->get_titulo_english();?>"
            >
            <label for="precio">*<?php echo ["en"=>"Price", "es"=>"Precio"][$lang];?>:</label>
            <input 
                type="number"
                step="0.01" 
                id="precio" 
                name="posted[precio]" 
                placeholder="<?php echo ["en"=>"Set the ad's price", "es"=>"Establezca el precio de la propiedad"][$lang];?>" 
                class="<?php echo $error==3 ? "input_error" : "";?>"
                value="<?php echo $propiedad->get_precio()?>"
            >
            <label for="imagen">*<?php echo ["en"=>"Image","es"=>"Imagen"][$lang];?>:</label>
            <input 
                type="file" 
                id="imagen" 
                accept="image/jpeg,image/png,image/webp"
                name="imagen"
                placeholder="Hola"
                class="<?php echo $error==4 ? "input_error" : ""?>"
            >
            <?php
                if(is_file(DEPLOYED_IMAGES_URL.$propiedad->get_imagen())):                     
            ?>
                <img src="/deployed_images/<?php echo $propiedad->get_imagen();?>" alt="Imagen de propiedad" class="formulario__imagen">
            <?php
                endif;
            ?>
            <label for="descripcion_espanol"><?php echo ["en"=>"Description (Spanish)","es"=>"Descripción (Español)"][$lang];?>:</label>
            <textarea 
                id="descripcion_espanol" 
                name="posted[descripcion_espanol]" 
                placeholder="<?php echo ["en"=>"Description of the ad in Spanish","es"=>"Descripción del anuncio en español"][$lang];?>"
                class="<?php echo $error==5 ? "input_error" : ""?>"
            ><?php echo $propiedad->get_descripcion_espanol();?></textarea>
            <label for="descripcion_english"><?php echo ["en"=>"Description (English)","es"=>"Descripción (Inglés)"][$lang];?>:</label>
            <textarea 
                id="descripcion_english" 
                name="posted[descripcion_english]" 
                placeholder="<?php echo ["en"=>"Description of the ad in English","es"=>"Descripción del anuncio en inglés"][$lang];?>"
                class="<?php echo $error==5 ? "input_error" : ""?>"
            ><?php echo $propiedad->get_descripcion_english();?></textarea>
        </fieldset>
        <fieldset>
            <legend><?php echo ["en"=>"Real state info","es"=>"Información de la propiedad"][$lang];?></legend>
            <label for="dormitorios">*<?php echo ["en"=>"Bedrooms","es"=>"Dormitorios"][$lang];?>:</label>
            <input 
                type="number" 
                id="dormitorios" 
                name="posted[dormitorios]" 
                placeholder="Ej.: 3" 
                min="1" max="20" 
                class="<?php echo $error==6 ? "input_error" : "";?>"
                value="<?php echo $propiedad->get_dormitorios()?>"
            >
            <label for="wc">*<?php echo ["en"=>"WC","es"=>"Baños"][$lang];?>:</label>
            <input 
                type="number" 
                id="wc" 
                name="posted[wc]" 
                placeholder="Ej.: 2" 
                min="1" max="20"
                class="<?php echo $error==7 ? "input_error" : "";?>"
                value="<?php echo $propiedad->get_wc()?>"
                >
            <label for="aparcamientos">*<?php echo ["en"=>"Parking places","es"=>"Aparcamientos"][$lang];?>:</label>
            <input 
                type="number" 
                id="aparcamientos" 
                name="posted[aparcamientos]" 
                placeholder="Ej.: 1" 
                min="1" max="20" 
                class="<?php echo $error==8? "input_error": "";?>"
                value="<?php echo $propiedad->get_aparcamientos()?>"
            >
        </fieldset>
        <fieldset>
            <legend>*<?php echo ["en"=>"Seller","es"=>"Vendedor"][$lang];?></legend>
            <select 
                name="posted[id_vendedor]" 
                class="<?php echo $error==9 ? "input_error" : ""?>"
            >
                <option disabled selected> -- <?php echo ["en"=>"Choose seller","es"=>"Elija un vendedor"][$lang];?> -- </option>
                <?php
                    foreach($vendedores as $vendedor):
                ?>
                    <option 
                        value="<?php echo $vendedor->get_id();?>"
                        <?php echo ($propiedad->get_id_vendedor()==$vendedor->get_id()) ? "selected" : "";?>
                    >
                        <?php echo $vendedor->get_nombre()." ".$vendedor->get_apellido();?>
                    </option>                    
                <?php
                    endforeach;
                ?>
            </select>
        </fieldset>
        <input type="submit" value="<?php echo $boton_text;?>" class="formulario__boton">
    </form>
</main>