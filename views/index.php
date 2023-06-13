<main class="container seccion">
    <h2><?php echo ["en"=>"More about us","es"=>"Más sobre nosotros"][$lang];?></h2>
    <?php
        include __DIR__."/templates/iconos-nosotros.php";
    ?>
</main>
<section class="seccion container">
    <h2><?php echo ["en"=>"Real state for sale","es"=>"Casas y pisos en venta"][$lang];?></h2>
    <?php
        include __DIR__."/templates/catalogo_anuncios.php";
    ?>
    <div class="ver-todas right-align">
        <a href="<?php echo "/$lang";?>/anuncios" class="ver-todas__boton"><?php echo ["en"=>"See all opportunities", "es"=>"Ver todas"][$lang];?></a>
    </div>
</section>
<section class="seccion atajo-contacto">
    <div class="container text-center">
        <h2><?php echo ["en"=>"Find your dreamed home", "es"=>"Encuentra la casa de tus sueños"][$lang];?></h2>
        <p><?php echo ["en"=>"Fill in the form and we will contact you as soon as possible", "es"=>"Rellena el formulario y un asesor se pondrá en contacto contigo rápidamente"][$lang];?></p>
        <a href="<?php echo "/$lang";?>/contacto" class="atajo-contacto__boton"><?php echo ["en"=>"Contact us", "es"=>"Contáctanos"][$lang];?></a>
    </div>
</section>
<section class="seccion container atajo-blog">
    <aside>
        <h3><?php echo ["en"=>"Our blog", "es"=>"Nuestro blog"][$lang];?></h3>
        <?php
            include __DIR__."/templates/entradas_blog.php";
        ?>
    </aside>
    <aside>
        <h3><?php echo ["en"=>"Opinions","es"=>"Opiniones"][$lang];?></h3>
        <div class="atajo-blog__opinion">
            <blockquote>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur fuga eveniet sapiente sed quae animi aliquam praesentium nesciunt corporis sequi laudantium similique, illum ut quod adipisci deleniti, reiciendis dicta perferendis!
            </blockquote>
            <p class="right-align">Máximo Décimo Meridio</p>
        </div>
    </aside>
</section>