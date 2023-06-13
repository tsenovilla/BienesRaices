<main class="container seccion narrow contacto">
    <?php
        echo $OK_alert;
    ?>
    <h1><?php echo ["en"=>"Contact us","es"=>"Contacto"][$lang];?></h1>
    <picture>
        <source srcset="/build/img/contacto.avif" type="image/avif">
        <source srcset="/build/img/contacto.webp" type="image/webp">
        <img loading="lazy" scr="/build/img/contacto.jpg" alt="Imagen contacto">
    </picture>
    <h4 class="text-center"><?php echo ["en"=>"Fill in the form to be contacted by us", "es"=>"Rellene el formulario para que nos pongamos en contacto con usted"][$lang];?></h4>
    <?php
        echo $KO_alert;
    ?>
    <form class="formulario formulario-contacto" method="POST">
        <fieldset>
            <legend><?php echo ["en"=>"Personal info", "es"=>"Información personal"][$lang];?></legend>
            <label for="nombre"><?php echo ["en"=>"First name","es"=>"Nombre"][$lang];?>:</label>
            <input
                type="text" 
                id="nombre" 
                placeholder= "<?php echo ["en"=>"Introduce your name","es"=>"Introduce tu nombre"][$lang];?>"
                name="nombre" 
                value="<?php echo $contacto->get_nombre();?>" 
                class="<?php echo $error==1 ? "input_error": "";?>"
            >
            <label for="apellido"><?php echo ["en"=>"Last name","es"=>"Apellidos"][$lang];?>: </label>
            <input 
                type="text" 
                id="apellido" 
                placeholder="<?php echo ["en"=>"Introduce your last name","es"=>"Introduce tus apellidos"][$lang];?>"
                name="apellido" 
                value="<?php echo $contacto->get_apellido();?>" 
                class="<?php echo $error==2 ? "input_error": "";?>"
            >
        </fieldset>
        <fieldset>
            <legend><?php echo ["en"=>"How can we help you?","es"=>"Motivo de la consulta"][$lang];?></legend>
            <label for="venta-compra"><?php echo ["en"=>"Are you looking for one of our opportunities or you prefer to announce a new one?","es"=>"¿Quiere comprar una de nuestras propiedades?¿O desea poner una a la venta?"][$lang];?></label>
            <select id="motivo_contacto" name="motivo_contacto" class="<?php echo $error==3 ? "input_error": "";?>">
                <option disabled selected>-- <?php echo ["en"=>"Choose one","es"=>"Seleccione una"][$lang];?> --</option>
                <option value="Compra" <?php echo $contacto->get_motivo_contacto()==="Compra" ? "selected" : "";?>><?php echo ["en"=>"Buy a real state","es"=>"Comprar una propiedad"][$lang];?></option>
                <option value="Vende" <?php echo $contacto->get_motivo_contacto()==="Vende" ? "selected" : "";?>><?php echo ["en"=>"Sell my place","es"=>"Vender mi casa"][$lang];?></option>
            </select>
            <label for="presupuesto"><?php echo ["en"=>"What is your budget?","es"=>"¿Cuál es su presupuesto?"][$lang];?></label>
            <input
                type="number" 
                id="presupuesto" 
                placeholder="<?php echo ["en"=>"Introduce your budget","es"=>"Introduzca su presupuesto"][$lang];?>"
                name="presupuesto" 
                value="<?php echo $contacto->get_presupuesto();?>" 
                class="<?php echo $error==4 ? "input_error": "";?>"
            >
            <label for="mensaje"><?php echo ["en"=>"Message","es"=>"Mensaje"][$lang];?>: </label>
            <textarea 
                id="mensaje" 
                placeholder="<?php echo ["en"=>"Briefly, explain why you are contacting us","es"=>"Describa brevemente el motivo de su consulta"][$lang];?>"
                name="mensaje" 
                class="<?php echo $error==5 ? "input_error": "";?>"
            ><?php echo $contacto->get_mensaje();?></textarea>
        </fieldset>
        <fieldset>
            <legend><?php echo ["en"=>"Contact details","es"=>"Detalles de contacto"][$lang];?></legend>
            <p><?php echo ["en"=>"How do you prefer to be contacted?","es"=>"¿Cómo desea ser contactado?"][$lang];?></p>
            <div class="formulario__radio">
                <div>
                    <label for="contactar-telefono"><?php echo ["en"=>"Phone","es"=>"Teléfono"][$lang];?></label>
                    <input 
                        type="radio" 
                        value="telefono" 
                        id="contactar-telefono" 
                        name="preferencia_contacto" 
                        <?php echo $contacto->get_preferencia_contacto()==="telefono" ? "checked" : "";?>
                    >
                </div>
                <div>
                    <label for="contactar-mail">E-mail</label>
                    <input 
                        type="radio" 
                        value="email" 
                        id="contactar-email" 
                        name="preferencia_contacto" 
                        <?php echo $contacto->get_preferencia_contacto()==="email" ? "checked" : "";?>
                    >
                </div>
            </div>
            <div class="contacto-telefono" style="display:none">
                <p><?php echo ["en"=>"Choose your time zone to find out when to call you. We DO NOT need your exact location, but somewhere close.","es"=>"Indíquenos su zona horaria para saber a que hora llamarle. NO necesitamos su ubicación exacta, pero sí algún lugar cercano."][$lang];?></p>
                <p class="timezone-select <?php echo $error==7 ? "input_error":"";?>"><?php echo $timezone ? $timezone: ["en"=>"Choose your time zone (drag the marker to the desired point)","es"=>"Seleccione su zona horaria (arrastre el marcador hasta el punto deseado)"][$lang];?></p>
                <label for="telefono"><?php echo ["en"=>"Phone","es"=>"Teléfono"][$lang];?>: </label>
                <div class="contacto-telefono__telefono-completo">
                    <input 
                        type="text" 
                        class="prefijo <?php echo $error==8 ? "input_error":"";?>" 
                        placeholder="<?php echo ["en"=>"International prefix","es"=>"Prefijo internacional"][$lang];?>"
                        value="<?php echo $prefijo;?>"
                    >
                    <input 
                        type="tel" 
                        id="telefono" 
                        placeholder="<?php echo ["en"=>"Introduce your phone number","es"=>"Introduzca su número de teléfono"][$lang];?>"
                        name="telefono" 
                        value="<?php echo $contacto->get_telefono();?>" 
                        class="<?php echo $error==9 ? "input_error": "";?>"
                    >
                    <div class="lista-paises"></div>
                </div>
                <label for="fecha"><?php echo ["en"=>"Date","es"=>"Fecha"][$lang];?>: </label>
                <input 
                    type="date" 
                    id="fecha" 
                    name="fecha" 
                    value="<?php echo $contacto->get_fecha();?>" 
                    class="<?php echo $error==10 ? "input_error": "";?>"
                >
                <label for="hora"><?php echo ["en"=>"Time","es"=>"Hora"][$lang];?>: </label>
                <input 
                    type="time" 
                    id="hora" 
                    name="hora" 
                    value="<?php echo $contacto->get_hora();?>" 
                    class="<?php echo $error==11 ? "input_error": "";?>"
                >
            </div>
            <div class="contacto-email" style="display:none">
                <label for="email">E-mail:</label>
                <input 
                    type="email" 
                    id="email" 
                    placeholder="<?php echo ["en"=>"Introduce your e-mail","es"=>"Introduzca su e-mail."][$lang];?>"
                    name="email" 
                    value="<?php echo $contacto->get_email();?>" 
                    class="<?php echo $error==12 ? "input_error": "";?>"
                >
            </div>
        </fieldset>
        <input type="submit" value="Enviar" class="formulario__boton">
    </form>
</main>