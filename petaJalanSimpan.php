<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peta_id = $_POST['peta_id'];
    $judul   = $_POST['judul'];
    $tahun   = $_POST['tahun'];
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE peta_jalan 
              SET judul = $1, tahun = $2, user_id = $3 
              WHERE peta_id = $4";
    
    $result = pg_query_params($koneksi, $query, [
        $judul,
        $tahun,
        $user_id,
        $peta_id
    ]);

    if ($result) {
        header("Location: petaJalanAdmin.php");
        exit;
    } else {
        echo "Gagal menyimpan data";
    }
}
?>
