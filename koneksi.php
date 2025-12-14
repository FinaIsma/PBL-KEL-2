<?php
$host = "localhost";
$port = "5432";         
$db   = "labNCS";
$user = "postgres";
$pass = "adhe0608";

$conn_string = "host=$host port=$port dbname=$db user=$user password=$pass";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Koneksi gagal: " . pg_last_error());
}
?>
