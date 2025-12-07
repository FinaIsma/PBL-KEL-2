<?php
// Konfigurasi database
$host     = "localhost";
$port     = "5432";
$dbname   = "labNCS";
$user     = "postgres";
$password = "12345";

try {
    // Buat koneksi menggunakan PDO
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    // Set error mode agar lebih mudah debug
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
