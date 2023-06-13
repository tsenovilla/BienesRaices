<!DOCTYPE html>
<?php // Iniciamos sesión en todas las páginas porque dependerá el contenido del header de si tiene permisos o no. Como es algo que es común a absolutamente todas las páginas, no lo pasamos por el controlador.
    if (!isset($_SESSION))
    {
        session_start();
    }
    $auth = $_SESSION["auth"] ?? false;
    // Title debe estar definido en el layout, si por lo que sea no se pasa, le damos valor por defecto. Si inicio no se indica, le asignamos null para que no muestre la página de inicio.
    $title = $title ?? "Bienes Raíces";
    $inicio = $inicio ?? null;
?>
<html lang="<?php echo $lang;?>">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title;?></title>
        <!-- Fuente principal de la página -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" crossorigin="crossorigin" as="font">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
        <!-- Hoja de estilos para el mapa interactivo Leaflet del contacto -->
        <link rel="preload" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" as="style">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
        <!-- Hoja de estilos de la web -->
        <link rel="preload" href="/build/css/styles.css" as="style">
        <link rel="stylesheet" href="/build/css/styles.css">
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <header class="header <?php echo $inicio ? "inicio" : "";?>">
            <div class="container contenido-header">
                <div class="barra-header">
                    <a href="<?php echo "/$lang/";?>">
                        <img class="logo" src="/build/img/logo.svg" alt="Logotipo de bienes raices">
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="drop-down" viewBox="0 0 612 792">
                        <path d="M21.857 226.607h568.286c12.072 0 21.857-9.785 21.857-21.857v-54.643c0-12.072-9.785-21.857-21.857-21.857H21.857C9.785 128.25 0 138.035 0 150.107v54.643c0 12.072 9.785 21.857 21.857 21.857zm0 218.572h568.286c12.072 0 21.857-9.785 21.857-21.857v-54.643c0-12.072-9.785-21.857-21.857-21.857H21.857C9.785 346.821 0 356.606 0 368.679v54.643c0 12.072 9.785 21.857 21.857 21.857zm0 218.571h568.286c12.072 0 21.857-9.785 21.857-21.857V587.25c0-12.072-9.785-21.857-21.857-21.857H21.857C9.785 565.393 0 575.178 0 587.25v54.643c0 12.072 9.785 21.857 21.857 21.857z"/>
                    </svg>
                    <div class="barra-header__aux"> <!-- Este div es solo para alinear la navegación y el botón de dark-mode. Con el flex de la barra-header queda mucho espacio sino. -->
                        <nav class="navegacion">
                            <a href="<?php echo "/$lang"?>/anuncios" class="navegacion__enlace"><?php echo ["es"=>"Anuncios","en"=>"On sale"][$lang];?></a>
                            <a href="<?php echo "/$lang"?>/blog" class="navegacion__enlace">Blog</a>
                            <a href="<?php echo "/$lang"?>/contacto" class="navegacion__enlace"><?php echo ["es"=>"Contacto","en"=>"Contact"][$lang];?></a>
                            <a href="<?php echo "/$lang"?>/nosotros" class="navegacion__enlace"><?php echo ["es"=>"Nosotros","en"=>"About Us"][$lang];?></a>
                            <?php 
                                if($auth):
                            ?>
                                    <a href="<?php echo "/$lang"?>/admin" class="navegacion__enlace"><?php echo ["es"=>"Administrador","en"=>"Admin"][$lang];?></a>
                                    <a href="<?php echo "/$lang"?>/logout" class="navegacion__enlace"><?php echo ["es"=>"Cerrar sesión","en"=>"Logout"][$lang];?></a>
                            <?php 
                                else: 
                            ?>
                                    <a href="<?php echo "/$lang"?>/login" class="navegacion__enlace"><?php echo ["es"=>"Iniciar sesión", "en"=>"Login"][$lang];?></a>
                            <?php 
                                endif;
                            ?> 
                            <div class="navegacion__idiomas">
                                <p class="navegacion__idiomas--selected"><?php echo strtoupper($lang);?><span>&#9660;</span></p>
                                <a href="/en<?php echo $route?>" class="navegacion__idiomas--idioma display-none">&#x1f1ec;&#x1f1e7; EN</a>
                                <a href="/es<?php echo $route?>" class="navegacion__idiomas--idioma display-none">&#x1f1ea;&#x1f1f8; ES</a>
                            </div>
                        </nav>
                        <svg 
                            aria-hidden="true" 
                            data-prefix="far" 
                            data-icon="moon" 
                            class="svg-inline--fa fa-moon fa-w-16 dark-mode-button" 
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 512 512"
                        >
                            <path d="M279.135 512c78.756 0 150.982-35.804 198.844-94.775 28.27-34.831-2.558-85.722-46.249-77.401-82.348 15.683-158.272-47.268-158.272-130.792 0-48.424 26.06-92.292 67.434-115.836 38.745-22.05 28.999-80.788-15.022-88.919A257.936 257.936 0 00279.135 0c-141.36 0-256 114.575-256 256 0 141.36 114.576 256 256 256zm0-464c12.985 0 25.689 1.201 38.016 3.478-54.76 31.163-91.693 90.042-91.693 157.554 0 113.848 103.641 199.2 215.252 177.944C402.574 433.964 344.366 464 279.135 464c-114.875 0-208-93.125-208-208s93.125-208 208-208z"/>
                        </svg>
                    </div>
                </div>
                <?php
                    if($inicio):
                ?>
                        <h1 class="contenido-header__texto"><?php echo ["es"=>"Venta de casas y pisos exclusivos de lujo","en"=>"Exclusive luxury houses and flats for sale"][$lang];?></h1>
                <?php
                    endif;
                ?>
            </div>
        </header>
        <?php 
            echo $page_content;
        ?>
        <footer class="footer seccion">
            <div class="container contenido-footer">

                <nav class="navegacion">
                    <a href="<?php echo "/$lang"?>/anuncios" class="navegacion__enlace"><?php echo ["es"=>"Anuncios","en"=>"On sale"][$lang];?></a>
                    <a href="<?php echo "/$lang"?>/blog" class="navegacion__enlace">Blog</a>
                    <a href="<?php echo "/$lang"?>/contacto" class="navegacion__enlace"><?php echo ["es"=>"Contacto","en"=>"Contact"][$lang];?></a>
                    <a href="<?php echo "/$lang"?>/nosotros" class="navegacion__enlace"><?php echo ["es"=>"Nosotros","en"=>"About Us"][$lang];?></a>
                </nav>
                <p class="copyright no-margin"><?php echo ["es"=>"Todos los derechos reservados","en"=>"All rights reserved"][$lang];?><?php echo date("Y");?> &copy;</p>
            </div>
        </footer>
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script> <!-- Script para el uso del mapa interactivo de Leaflet. -->
        <script src="/build/js/app.js"></script>
    </body>
</html>