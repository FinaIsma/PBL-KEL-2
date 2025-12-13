<?php
$koneksi = pg_connect("host=localhost dbname=labNCS user=postgres password=azon701");
if (!$koneksi) {
    die("Koneksi gagal: " . pg_last_error());
}
