<?php
include("koneksi.php");

if (!isset($_GET['peta_id'])) {
    die("ID Peta Jalan tidak ditemukan.");
}

$peta_id = $_GET['peta_id'];

// Hapus data
$deleteQuery = "DELETE FROM peta_jalan WHERE peta_id=$1";
$deleteResult = pg_query_params($koneksi, $deleteQuery, [$peta_id]);

if ($deleteResult) {
    header("Location: petaJalanTabel.php");
    exit;
} else {
    echo "Gagal menghapus data: " . pg_last_error($koneksi);
}
?>
