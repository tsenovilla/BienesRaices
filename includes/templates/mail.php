<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .copia-mensaje
            {
                background-color: #e1e1e1;
                padding: 2rem;
                border-radius: 1rem;
                font-size: 10px;
            }
            .italic
            {
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <p class="italic"><?php echo ["en"=>"This message has been automatically sent, please, do not reply to it. If you do not recognize this mail, please ignore it.","es"=>"Este mensaje ha sido generado automáticamente, por favor no responda a esta dirección de correo. Si no le suena de nada este mail, puede ignorarlo."][$lang];?></p>
        <p><?php echo ["en"=>"Hi $nombre!","es"=>"¡Hola $nombre!"][$lang]?></p>
        <p>
            <?php
                echo ["en"=>"We are glad to communicate you that we hace correctly received your contact request to ".($motivo_contacto==="Compra" ? "buy one of the real states announced at our website":"sell a property in our website"),"es"=>"Le comunicamos que hemos recibido correctamente su petición de contacto para".($motivo_contacto==="Compra" ? "comprar una de las propiedades anunciadas en nuestro portal." : "vender una propiedad a través de nuestro portal.")][$lang];
            ?>
        </p>
        <p><?php echo ["en"=>"One of our agents will contact you as soon as possible.", "es"=>"Uno de nuestros agentes se pondrá en contacto contigo a la mayor brevedad posible."][$lang];?></p>
        <p class="copia-mensaje italic"><?php echo ["en"=>"Copy of the message sent with the contact request","es"=>"Copia del mensaje enviado a la plataforma"][$lang];?><br><?php echo $mensaje;?></p>
        <p><?php echo ["en"=>"Best regards,","es"=>"Atentamente,"][$lang];?></p>
        <p><?php echo ["en"=>"The team of","es"=>"El equipo de"][$lang];?> Bienes Raíces.</p>
    </body>
</html>