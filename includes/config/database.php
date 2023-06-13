<?php
    /**
     * This functions perform the connection to de database. If the connection succeeds, it returns a mysqli object. Otherwise, the execution fails and the website is not loaded.
     */
    function DB_connect() : PDO
    {
        return new PDO(
            "mysql:host=".$_ENV["DB_HOST"].";dbname=".$_ENV["DB_NAME"],
            $_ENV["DB_USER"],
            $_ENV["DB_PASS"]
        );
    }
