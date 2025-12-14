<?php
include "koneksi.php";

// Pastikan ada ID
if (!isset($_GET['id'])) {
    die("Error: ID tidak ditemukan.");
}

$id = $_GET['id'];

// Query delete
$query = "DELETE FROM agenda WHERE agenda_id = $1";
$delete = pg_query_params($conn, $query, [$id]);

if ($delete) {
    // Setelah delete berhasil → balik ke tabel
    header("Location: tabelAgenda.php");
    exit;
} else {
    echo "Gagal menghapus data.";
}
?>
