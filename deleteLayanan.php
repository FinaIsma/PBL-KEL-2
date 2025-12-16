<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("db.php"); 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Hapus data layanan
    pg_query($conn, "DELETE FROM layanan WHERE layanan_id = $id");
}

// Redirect kembali ke tabel layanan
header("Location: tabelLayanan.php");
exit;
?>
