<main class="container seccion narrow">
    <h1><?php echo ["en"=>"Login as admin","es"=>"Iniciar sesión como adminsitrador"][$lang];?></h1>
    <p class="text-center"><?php echo ["en"=>"Every field is mandatory","es"=>"Todos los campos son obligatorios"][$lang];?></p>
    <?php
        echo $alert;
    ?>
    <form class="formulario" method="POST">
        <fieldset>
            <label for="email">E-mail:</label>
            <input 
                type="email" 
                id="email"
                name="email" 
                placeholder="<?php echo ["en"=>"Introduce an admin e-mail","es"=>"Introduzca un e-mail de administrador"][$lang];?>" 
                class="<?php echo $error==1 ? "input_error" : "";?>" 
                value="<?php echo $usuario->get_email();?>"
            >
            <label for="password"><?php echo ["en"=>"Password","es"=>"Contraseña"][$lang];?>: </label>
            <div class="password <?php echo $error==2 ? "input_error" : "";?>">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="<?php echo ["en"=>"Introduce your password","es"=>"Introduzca su contraseña"][$lang];?>"
                    class="password__text"
                    value="<?php echo $usuario->get_password();?>"
                >
                <svg 
                    viewBox="0 0 24 24" 
                    class="password__button"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5C8.243 5 5.436 7.44 3.767 9.44a3.96 3.96 0 000 5.12C5.436 16.56 8.243 19 12 19c3.757 0 6.564-2.44 8.233-4.44a3.96 3.96 0 000-5.12C18.564 7.44 15.757 5 12 5z" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg viewBox="0 0 24 24" 
                    xmlns="http://www.w3.org/2000/svg"
                    class="password__button"
                    style="display:none"
                >
                    <path d="M9.764 5.295A8.619 8.619 0 0112 5c3.757 0 6.564 2.44 8.233 4.44a3.96 3.96 0 010 5.12c-.192.23-.4.466-.621.704M12.5 9.04a3.002 3.002 0 012.459 2.459M3 3l18 18m-9.5-6.041A3.004 3.004 0 019.17 13M4.35 8.778c-.208.223-.402.445-.582.661a3.961 3.961 0 000 5.122C5.435 16.56 8.242 19 12 19a8.62 8.62 0 002.274-.306" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </fieldset>
        <input type="submit" value="<?php echo ["en"=>"Login","es"=>"Iniciar sesión"][$lang];?>" class="formulario__boton">
    </form>
</main>