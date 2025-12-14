<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Hapus data layanan
    pg_query($conn, "DELETE FROM profil WHERE profil_id = $id");
}

// Redirect kembali ke tabel layanan
header("Location: tabelProfil.php");
exit;
?>
