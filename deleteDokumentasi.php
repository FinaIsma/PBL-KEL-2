<?php
include "koneksi.php";

// Cek apakah parameter ID dikirim
if (!isset($_GET['dokumentasi_id'])) {
    die("Error: ID tidak ditemukan.");
}

$id = $_GET['dokumentasi_id'];

// Ambil nama file media dari database
$query = "SELECT media_path FROM dokumentasi WHERE dokumentasi_id = $1";
$result = pg_query_params($conn, $query, [$id]);

if (!$result) {
    die("Error query: " . pg_last_error($conn));
}

$row = pg_fetch_assoc($result);

if ($row) {
    $mediaFile = $row['media_path'];

    // Hapus file fisik dari folder upload jika ada
    if ($mediaFile && file_exists("upload/$mediaFile")) {
        unlink("upload/$mediaFile");
    }

    // Hapus data dari database
    $deleteQuery = "DELETE FROM dokumentasi WHERE dokumentasi_id = $1";
    $deleteResult = pg_query_params($conn, $deleteQuery, [$id]);

    if (!$deleteResult) {
        die("Error hapus data: " . pg_last_error($conn));
    }

    // Redirect kembali ke tabelDokumentasi.php
    header("Location: tabelDokumentasi.php");
    exit();

} else {
    die("Error: Data dengan ID $id tidak ditemukan.");
}
?>
