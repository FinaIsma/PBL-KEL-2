<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("db.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Hapus file foto jika ada
    $result = pg_query($conn, "SELECT foto FROM pengelola_lab WHERE pengelola_id = $id");
    if ($row = pg_fetch_assoc($result)) {
        if (file_exists($row['foto'])) unlink($row['foto']);
    }

    // Hapus data di DB
    pg_query($conn, "DELETE FROM pengelola_lab WHERE pengelola_id = $id");
}

// Redirect kembali ke tabel
header("Location: tabelPengelola.php");
exit;
?>
