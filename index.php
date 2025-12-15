<?php
require_once "backend/config.php";

// Ambil Bidang Fokus
$bidang = $db->query("SELECT * FROM bidang_fokus ORDER BY bidangfokus_id DESC LIMIT 3");

// Ambil Peta Jalan
$peta = $db->query("SELECT * FROM peta_jalan ORDER BY tahun ASC");

// Ambil Galeri
$galeri = $db->query("SELECT * FROM dokumentasi ORDER BY dokumentasi_id DESC LIMIT 10");

// Ambil Produk
$produk = $db->query("SELECT * FROM layanan ORDER BY layanan_id DESC LIMIT 4");

// Ambil Arsip
$arsip = $db->query("SELECT * FROM arsip ORDER BY arsip_id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Network & Cyber Security</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="assets/css/pages/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- PDF.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <style>
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
</head>

<body>

<!-- NAVBAR -->
<div id="navbar-placeholder"></div>
<script src="assets/js/navbar.js"></script>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-text">
            <h1>Network & Cyber Security</h1>
            <h2>Laboratorium</h2>
            <p>
                Laboratorium ini adalah unit pendukung akademik di Jurusan Teknologi Informasi
                yang berfokus pada pengembangan kompetensi jaringan komputer dan keamanan siber.
            </p>
            <a href="profil.php" class="hero-btn">Selengkapnya</a>
        </div>

        <img src="assets/img/pixel_cysec.png" alt="Hero Image" class="hero-image">
    </div>
</section>

<!-- FOOTER -->
<div id="footer-placeholder"></div>
<script src="assets/js/footer.js"></script>
<script src="assets/js/landing.js"></script>

<!-- PDF THUMBNAIL SCRIPT -->
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

    document.querySelectorAll(".pdf-thumb").forEach(canvas => {
        const pdfUrl = canvas.dataset.pdf;

        pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 0.35 });
                const context = canvas.getContext("2d");

                canvas.width = viewport.width;
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
</html>
