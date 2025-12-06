<?php
include("koneksi-bidang.php");

// Pastikan ada ID
if (!isset($_GET['id'])) {
    die("ID Bidang Fokus tidak ditemukan.");
}
$id = $_GET['id'];

$deleteQuery = "DELETE FROM bidang_fokus WHERE bidangfokus_id = $1";
$deleteResult = pg_query_params($koneksi, $deleteQuery, [$id]);

if ($deleteResult) {
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'tabelBidang.php';
          </script>";
    exit;
} else {
    echo "<script>
            alert('Gagal menghapus data!');
            window.location.href = 'tabelBidang.php';
          </script>";
}
?>
