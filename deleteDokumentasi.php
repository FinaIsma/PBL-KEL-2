<?php
include("backend/config.php");

if (!isset($_POST['id'])) {
    header("Location: tabelDokumentasi.php");
    exit;
}

$id = intval($_POST['id']);

try {
    // Ambil media_path dulu
    $stmt = $db->prepare("SELECT media_path FROM dokumentasi WHERE dokumentasi_id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $filePath = $data['media_path'];

        // Hapus file fisik kalau ada
        if (!empty($filePath) && file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dari DB
        $del = $db->prepare("DELETE FROM dokumentasi WHERE dokumentasi_id = ?");
        $del->execute([$id]);
    }

    header("Location: tabelDokumentasi.php");
    exit;

} catch (PDOException $e) {
    die("Gagal menghapus data: " . $e->getMessage());
}
