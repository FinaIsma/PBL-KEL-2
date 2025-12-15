<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/backend/config.php";

// Pastikan ada ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: arsip.php");
    exit;
}

$id = (int) $_GET['id'];

try {
    // Query aman dengan PDO
    $stmt = $db->prepare("
        SELECT * 
        FROM arsip 
        WHERE arsip_id = :id
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan
    if (!$data) {
        echo "<script>
                alert('Arsip tidak ditemukan');
                window.location='arsipAdmin.php';
              </script>";
        exit;
    }

} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
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

    <!-- PDF.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
</head>

<body>

    <!-- NAVBAR -->
    <div id="header" style="margin-bottom: -120px;"></div>

    <div class="page-container">
        <div class="event-card">

            <!-- BAGIAN KIRI -->
            <div class="event-left">
                <a href="arsipAdmin.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <h1 class="event-title"><?= htmlspecialchars($data['judul']); ?></h1>

                <p class="event-description">
                    <?= htmlspecialchars($data['deskripsi']); ?>
                </p>

                <div class="event-info">
                    <div class="info-row">
                        <span class="info-label">Penulis:</span>
                        <span class="info-value"><?= htmlspecialchars($data['penulis']); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value"><?= date("d M Y", strtotime($data['tanggal'])); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Kategori:</span>
                        <span class="info-value"><?= htmlspecialchars($data['kategori']); ?></span>
                    </div>
                </div>
            </div>

            <!-- BAGIAN KANAN -->
            <div class="event-right">
                <!-- THUMBNAIL PDF AUTO GENERATE -->
                <div class="arsip-thumbnail">
                    <canvas 
                        class="pdf-thumb"
                        data-pdf="upload/<?= htmlspecialchars($data['file_path']); ?>">
                    </canvas>
                </div>

                <a href="upload/<?= htmlspecialchars($data['file_path']); ?>" 
                   class="download-button" 
                   download>
                   Unduh
                </a>
            </div>

            <!-- PREVIEW PDF BESAR -->
            <div class="event-image-large">
                <iframe 
                    src="upload/<?= htmlspecialchars($data['file_path']); ?>" 
                    width="100%" 
                    height="750px" 
                    style="border: none; border-radius: 10px;">
                </iframe>
            </div>

        </div>
    </div>

    <script src="assets/js/sidebarHeader.js"></script>

    <!-- SCRIPT GENERATE THUMBNAIL PDF -->
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

        document.querySelectorAll(".pdf-thumb").forEach(canvas => {
            const pdfUrl = canvas.dataset.pdf;

            pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
                pdf.getPage(1).then(page => {

                    const viewport = page.getViewport({ scale: 0.35 });
                    const context = canvas.getContext("2d");

                    canvas.width  = viewport.width;
                    canvas.height = viewport.height;

                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    });
                });
            }).catch(err => {
                console.error("PDF error:", err);
            });
        });
    </script>

</body>

<style>
.navbar {
    background-color: #fff !important;
}

.navbar.scrolled {
    backdrop-filter: blur(6px) !important;
    box-shadow: 3px 5px 10px rgba(0, 0, 0, 0.15) !important;
}

/* THUMBNAIL PDF */
.arsip-thumbnail {
    width: 80px;
    flex-shrink: 0;
    overflow: hidden;
}

.pdf-thumb {
    width: 100%;
    height: auto;
    display: block;
    background: #f1f1f1;
    border-radius: 4px;
}
</style>
</html>
