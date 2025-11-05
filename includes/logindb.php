<?php
$db_host = "localhost";
$db_port = "5432";
$db_name = "yourdatabasename";
$db_user = "postgres";
$db_pass = "yourpassword"; 

$connection = pg_connect("host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass");

if (!$connection) {
    die("PostgreSQL connection failed: " . pg_last_error());
}
?>