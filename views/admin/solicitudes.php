<main class="container seccion">
    <?php
        include __DIR__."/../templates/admin_back.php";
    ?>
    <?php
        echo $KO_alert;
    ?>
    <h1><?php echo ["en"=>"Contact requests", "es"=>"Solicitudes de contacto"][$lang];?></h1>
    <div class="contenedor-solicitudes">
        <?php
            foreach($solicitudes as $solicitud):
        ?>
            <div class="solicitud">
                <p class="solicitud__nombre"><?php echo ["en"=>"Name","es"=>"Nombre"][$lang];?>:</br> <?php echo $solicitud->get_nombre()." ".$solicitud->get_apellido();?></p>
                <p><?php echo ["en"=>"Looking for:", "es"=>"Motivo de contacto"][$lang];?>: </br><?php echo $solicitud->get_motivo_contacto() === "Compra" ? ["en"=>"Purchase","es"=>"Compra"][$lang] : ["en"=>"Sell", "es"=>"Venta"][$lang];?><p>
                <p><?php echo ["en"=>"What is your budget?","es"=>"¿Cuál es su presupuesto?"][$lang];?>:</br>$<?php echo $solicitud->get_presupuesto();?><p>
                <p class="solicitud__mensaje"><?php echo ["en"=>"Message","es"=>"Mensaje"][$lang];?>: </br><?php echo $solicitud->get_mensaje();?><p>
                <div class="solicitud__detalles-contacto">
                    <?php
                        if($solicitud->get_preferencia_contacto()==="email"):
                    ?>
                            <img src="/build/img/mail.svg" alt="Icono email" class="solicitud__detalles-contacto--icono">
                            <p><?php echo $solicitud->get_email();?></p>

                </div>
                <p class="solicitud__relleno"></p><!-- Este parrafo no se llena con nada, es solo para darle la altura correcta a los contenedores de solicitudes, ya que en el caso de seleccionar teléfono, tienen un poco más de información y hay que compensarlo con un espacio. -->
                    <?php
                        else:
                    ?>
                            <img src="/build/img/phone.svg" alt="Icono teléfono" class="solicitud__detalles-contacto--icono">
                            <p><?php echo $solicitud->get_telefono();?></p>
                </div>
                <p class="solicitud__fecha"><?php echo ["en"=>"Chosen date", "es"=>"Fecha escogida"][$lang];?>: <?php echo $solicitud->get_fecha()." ".$solicitud->get_hora();?></p>
                <?php
                    endif;
                ?>
                <p class="solicitud__gestionada" id="<?php echo $solicitud->get_id();?>"><?php echo ["en"=>"Managed request","es"=>"Solicitud tratada"][$lang];?></p>
            </div>
        <?php
            endforeach;
        ?>
    </div>
    <?php 
        if(!$solicitudes):
    ?>
            <h2 class="text-center"><?php echo ["en"=>"There's not any contact request at this time","es"=>"No hay solicitudes de contacto en este momento"][$lang];?></h2>
    <?php 
        endif;
    ?>
</main>