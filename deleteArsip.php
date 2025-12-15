<?php
session_start();
require_once "backend/config.php";

// optional: proteksi login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    try {
        $stmt = $db->prepare(
            "DELETE FROM arsip WHERE arsip_id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

    } catch (PDOException $e) {
        die("Gagal hapus: " . $e->getMessage());
    }
}

header("Location: arsipTabel.php");
exit;
