<?php
include("koneksi.php"); // file koneksi ke DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $peta_id = $_POST['peta_id'];
    $judul = $_POST['judul'];
    $tahun = $_POST['tahun'];

    // Update data di database
    $query = "UPDATE peta_jalan 
              SET judul = $1, tahun = $2 
              WHERE peta_id = $3";
    
    $result = pg_query_params($koneksi, $query, [$judul, $tahun, $peta_id]);

    if ($result) {
        // Redirect ke halaman admin setelah berhasil
        header("Location: petaJalanAdmin.php");
        exit;
    } else {
        echo "Gagal menyimpan data: " . pg_last_error($koneksi);
    }
}
?>
