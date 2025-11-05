<?php
    $connection = pg_connect("host=localhost port=5432 dbname=logindb user=postgres password=eonpassword");
    if (!$connection) {
        die( "PostgreSQL connection failed." . pg_last_error());
    }

    $userTable = pg_query($connection, "
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(100) NOT NULL)"
        );

?>