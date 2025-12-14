<?php
include 'koneksi.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$start = ($page - 1) * $limit;

// Ambil agenda
$agendaQuery = pg_query($conn, "SELECT agenda_id, hari_tgl, judul, deskripsi FROM agenda ORDER BY hari_tgl ASC");
if (!$agendaQuery) {
    die("Query agenda gagal: " . pg_last_error());
}

// Ambil dokumentasi sesuai page
$dokumentasiQuery = pg_query($conn, "
    SELECT dokumentasi_id, judul, media_path
    FROM dokumentasi
    ORDER BY dokumentasi_id ASC
    OFFSET $start LIMIT $limit
");
if (!$dokumentasiQuery) {
    die("Query dokumentasi gagal: " . pg_last_error());
}

// Hitung total halaman dokumentasi
$totalResult = pg_query($conn, "SELECT COUNT(*) AS total FROM dokumentasi");
$totalItems = pg_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalItems / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Galeri - Network & Cyber Security Lab</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="assets/css/pages/galeri.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div id="navbar-placeholder"></div>
    <script src="assets/js/navbar.js"></script>

    <section class="hero-section">
        <h1>Galeri</h1>
    </section>

    <section class="agenda-section">
        <h2 class="section-title">Agenda Mendatang</h2>
        <div class="agenda-wrapper">
            <button class="scroll-btn left"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="agenda-container">
                <?php while ($row = pg_fetch_assoc($agendaQuery)): ?>
                    <div class="agenda-card">
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                        <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                        <p><small>Tanggal: <?= htmlspecialchars($row['hari_tgl']) ?></small></p>
                    </div>
                <?php endwhile; ?>
            </div>
            <button class="scroll-btn right"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

    <section class="dokumentasi-section" id="dokumentasi">
        <h2 class="section-title">Dokumentasi</h2>
        <div class="dokumentasi-container">
            <?php while ($row = pg_fetch_assoc($dokumentasiQuery)): ?>
                <div class="dokumentasi-card">
                    <div class="dokumentasi-image" style="background-image: url('assets/img/<?= htmlspecialchars($row['media_path']) ?>')"></div>
                    <div class="dokumentasi-info">
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="galeri.php?page=<?= $i ?>#dokumentasi" 
                    class="pagination-btn <?= ($i==$page?'active':'') ?>">
                    <?= $i ?>
                </a>

            <?php endfor; ?>
        </div>
    </section>

    <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>

    <!-- Scroll tombol agenda -->
    <script>
        const agendaContainer = document.querySelector(".agenda-container");
        document.querySelector(".scroll-btn.left").onclick = () => {
            agendaContainer.scrollBy({ left: -300, behavior: "smooth" });
        };
        document.querySelector(".scroll-btn.right").onclick = () => {
            agendaContainer.scrollBy({ left: 300, behavior: "smooth" });
        };
    </script>
</body>
</html>