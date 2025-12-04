<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Hapus file media jika ada
    $result = pg_query($conn, "SELECT media_path FROM sarana_prasarana WHERE sarpras_id = $id");
    if ($row = pg_fetch_assoc($result)) {
        if (!empty($row['media_path']) && file_exists($row['media_path'])) {
            unlink($row['media_path']);
        }
    }

    // Hapus data di DB
    pg_query($conn, "DELETE FROM sarana_prasarana WHERE sarpras_id = $id");
}

// Redirect kembali ke tabel
header("Location: tabelSarpras.php");
exit;
?>
