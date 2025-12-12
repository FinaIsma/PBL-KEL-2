<?php
// Panggil koneksi PostgreSQL
require_once "koneksi.php";

// Pastikan ada ID di URL
if (!isset($_GET['id'])) {
    header("Location: arsip.php");
    exit;
}

$id = $_GET['id'];

// Ambil data menggunakan query aman (menghindari SQL injection)
$query = "SELECT * FROM arsip WHERE arsip_id = $1";
$result = pg_query_params($conn, $query, [$id]);
$data = pg_fetch_assoc($result);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Arsip tidak ditemukan'); window.location='arsip.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Arsip</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/arsipDetail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- NAVBAR -->
    <div id="navbar-placeholder"></div>
    <script src="assets/js/navbar.js"></script>

    <header class="arsip-hero">
        <h1>Arsip</h1>
    </header>

    <div class="page-container">
        <div class="event-card">

            <!-- BAGIAN KIRI -->
            <div class="event-left">
                <a href="arsipAdmin.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <h1 class="event-title"><?= $data['judul']; ?></h1>

                <p class="event-description">
                    <?= $data['deskripsi']; ?>
                </p>

                <div class="event-info">
                    <div class="info-row">
                        <span class="info-label">Penulis:</span>
                        <span class="info-value"><?= $data['penulis']; ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value"><?= date("d M Y", strtotime($data['tanggal'])); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Kategori:</span>
                        <span class="info-value"><?= $data['kategori']; ?></span>
                    </div>
                </div>
            </div>

            <!-- BAGIAN KANAN -->
            <div class="event-right">
                <div class="event-thumbnail-small">
                    <img src="upload/<?= $data['thumbnail']; ?>" 
                         alt="Thumbnail"
                         style="width:100%; border-radius: 8px;">
                </div>

                <a href="upload/<?= $data['file_path']; ?>" 
                   class="download-button" 
                   download>
                   Unduh
                </a>
            </div>

            <!-- PREVIEW PDF BESAR -->
            <div class="event-image-large">
                <iframe 
                    src="upload/<?= $data['file_path']; ?>" 
                    width="100%" 
                    height="750px" 
                    style="border: none; border-radius: 10px;">
                </iframe>
            </div>

        </div>
    </div>

</body>
</html>
