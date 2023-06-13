<main class="container seccion">
    <?php
        include __DIR__."/../templates/admin_back.php";
    ?>
    <h1><?php echo ["en"=>"Add, edit or delete the sellers registered in our agency", "es"=>"Añada, edite o elimine los vendedores registrados en la inmobiliaria"][$lang];?></h1>
    <p class="text-center small-font-size"><?php echo ["en"=>"Felds marked with * are mandatory","es"=>"Los campos marcados con * son obligatorios"][$lang];?></p>
    <?php
        echo $KO_alert;
    ?>
    <div class="admin-list">
        <div class="admin-list__content">
            <p class="text-center admin-list__header"><?php echo ["en"=>"First name","es"=>"Nombre"][$lang];?></p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Last name","es"=>"Apellidos"][$lang];?></p>
            <p class="text-center admin-list__header">E-mail</p>
            <p class="text-center admin-list__header"><?php echo ["en"=>"Actions","es"=>"Acciones"][$lang];?></p>
        </div>
        <!-- Formulario para el nuevo vendedor -->
        <form
            method="POST" 
            id="0" 
        >
        </form>
        <div class="admin-list__content">
            <div class="formulario">
                <label for="nombre"><?php echo ["en"=>"First name","es"=>"Nombre"][$lang];?>:</label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="posted[nombre]" 
                    placeholder="<?php echo ["en"=>"Seller's first name","es"=>"Nombre del vendedor"][$lang];?>"
                    form="0"
                    class="<?php echo $submit_type==["en"=>"Create","es"=>"Crear"][$lang] && $error==1 ? "input_error" : "";?>" 
                    value="<?php echo $nuevo_vendedor->get_nombre();?>"
                >
            </div>
            <div class="formulario">
            <label for="apellido"><?php echo ["en"=>"Last name","es"=>"Apellidos"][$lang];?>:</label>
                <input 
                    type="text" 
                    id="apellido" 
                    name="posted[apellido]" 
                    placeholder="<?php echo ["en"=>"Seller's last name","es"=>"Apellidos del vendedor"][$lang];?>"
                    form="0"
                    class="<?php echo $submit_type==["en"=>"Create","es"=>"Crear"][$lang] && $error==2 ? "input_error" : "";?>" 
                    value="<?php echo $nuevo_vendedor->get_apellido();?>"
                >
            </div>
            <div class="formulario">
                <label for="email">E-mail: </label>
                <input 
                    type="text" 
                    id="email" 
                    name="posted[email]" 
                    placeholder="E-mail"
                    form="0"
                    class="<?php echo $submit_type==["en"=>"Create","es"=>"Crear"][$lang] && $error==3 ? "input_error" : "";?>" 
                    value="<?php echo $nuevo_vendedor->get_email();?>"
                >
            </div>
            <input type="submit" class="admin-list__crear width_100" name="submit_type" value="<?php echo ["en"=>"Create","es"=>"Crear"][$lang];?>" form="0">
        </div>
        <?php
            foreach($vendedores as $vendedor):
                if($id == $vendedor->get_id() && $submit_type===["en"=>"Update","es"=>"Actualizar"][$lang])
                {
                    $vendedor = $vendedor_to_update;
                }
        ?>
                <form 
                    method="POST" 
                    action="<?php echo "/$lang";?>/admin/vendedores?id=<?php echo $vendedor->get_id();?>"
                    id="<?php echo $vendedor->get_id();?>" 
                ></form>
                <div class="admin-list__content">
                    <div class="formulario">
                        <label for="nombre"><?php echo ["en"=>"First name","es"=>"Nombre"][$lang];?>:</label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="posted[nombre]" 
                            placeholder="<?php echo ["en"=>"Seller's first name","es"=>"Nombre del vendedor"][$lang];?>"
                            form="<?php echo $vendedor->get_id();?>"
                            class="<?php echo $vendedor==$vendedor_to_update && $error==1 ? "input_error" : "";?>" 
                            value="<?php echo $vendedor->get_nombre();?>"
                        >
                    </div>
                    <div class="formulario">
                        <label for="apellido"><?php echo ["en"=>"Last name","es"=>"Apellidos"][$lang];?> :</label>
                        <input 
                            type="text" 
                            id="apellido" 
                            name="posted[apellido]" 
                            placeholder="<?php echo ["en"=>"Seller's last name","es"=>"Apellidos del vendedor"][$lang];?>"
                            form="<?php echo $vendedor->get_id();?>"
                            class="<?php echo $vendedor==$vendedor_to_update && $error==2 ? "input_error" : "";?>" 
                            value="<?php echo $vendedor->get_apellido();?>"
                        >
                    </div>
                    <div class="formulario">
                        <label for="email">E-mail: </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="posted[email]" 
                            placeholder="E-mail"
                            form="<?php echo $vendedor->get_id();?>"
                            class="<?php echo $vendedor==$vendedor_to_update && $error==3 ? "input_error" : "";?>" 
                            value="<?php echo $vendedor->get_email();?>"
                        >
                    </div>
                    <div class="admin-list__acciones">
                        <input type="submit" class="admin-list__acciones--accion width_100" name="submit_type" value="<?php echo ["en"=>"Update","es"=>"Actualizar"][$lang];?>" form="<?php echo $vendedor->get_id();?>">
                        <input type="submit" class="admin-list__acciones--accion width_100" name="submit_type" value="<?php echo ["en"=>"Delete", "es"=>"Eliminar"][$lang];?>" form="<?php echo $vendedor->get_id();?>">
                    </div>
                </div><!--.admin-list__content-->
        <?php 
            endforeach;
        ?>
    </div>
</main>