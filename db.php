<?php
$host = "localhost";
$port = "5432";
$db   = "labNCS";
$user = "postgres";
$pass = "12345";

$conn_string = "host=$host port=$port dbname=$db user=$user password=$pass";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Koneksi ke PostgreSQL gagal.");
}
?>
