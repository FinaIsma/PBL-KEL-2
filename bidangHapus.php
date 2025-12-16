<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("backend/config.php"); 

// Pastikan ada ID
if (!isset($_POST['id'])) {
    die("ID Bidang Fokus tidak ditemukan.");
}

$id = intval($_POST['id']);

try {
    $stmt = $db->prepare("DELETE FROM bidang_fokus WHERE bidangfokus_id = ?");
    $stmt->execute([$id]);

    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'tabelBidang.php';
          </script>";
    exit;

} catch (PDOException $e) {
    echo "<script>
            alert('Gagal menghapus data!');
            window.location.href = 'tabelBidang.php';
          </script>";
}
?>
