<main class="main container seccion">
    <h1><?php echo ["en"=>"Who we are","es"=>"Conócenos"][$lang];?></h1>
    <div class="nosotros">
        <div>
            <picture>
                <source scrset="/build/img/nosotros.avif" type="image/avif">
                <source scrset="/build/img/nosotros.webp" type="image/webp">
                <img src="/build/img/nosotros.jpg" alt="Imagen principal página nosotros" loading="lazy">
            </picture>
        </div>
        <div>
            <h4><?php echo ["en"=>"25 years of experience","es"=>"25 años de experiencia"][$lang];?></h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Libero fugit consequuntur vel consequatur magnam, ipsum natus minus quaerat maiores distinctio assumenda asperiores repellat sequi pariatur ex. Cumque iste rem placeat? Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nobis asperiores maxime, dolorem minima sapiente nam, quis suscipit et consectetur voluptates dolore, commodi fugiat nemo expedita impedit perspiciatis a adipisci facere.
            </p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perferendis optio deserunt commodi, distinctio veritatis doloribus recusandae soluta cumque dolore? Cupiditate totam aperiam eum est distinctio? Vero perferendis omnis fugit quaerat!</p>
        </div>
    </div>
    <h2><?php echo ["en"=>"More about us","es"=>"Más sobre nosotros"][$lang];?></h2>
    <?php
        include __DIR__."/templates/iconos-nosotros.php";
    ?>
</main>