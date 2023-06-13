<?php

    // Ruta de las imagenes subidas a la base de datos
    define("DEPLOYED_IMAGES_URL", __DIR__."/../public/deployed_images/");

    // Incluir autoload de clases
    require __DIR__ . "/../vendor/autoload.php";
    // Añadimos variables de entorno
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
    // Funciones 
    require "functions.php";
    // Incluir config database
    require "config/database.php";

    // Conexión a la base de datos, en todas las páginas tenemos las clases conectadas a la base de datos
    use Model\BienesRaicesDB;
    BienesRaicesDB::openDB();
