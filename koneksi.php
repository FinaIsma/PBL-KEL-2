<?php
$koneksi = pg_connect("host=localhost port=5432 dbname=labNCS user=postgres password=adhe0608");

if (!$koneksi) {
    die("Koneksi gagal: " . pg_last_error());
}
?>
