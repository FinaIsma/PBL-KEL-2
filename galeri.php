<?php
session_start();
include("koneksi.php"); 

$agenda = [];
$agendaQuery = pg_query($koneksi, "SELECT * FROM agenda ORDER BY hari_tgl ASC");

if (!$agendaQuery) {
    die("Query agenda gagal: " . pg_last_error($koneksi));
}

while ($a = pg_fetch_assoc($agendaQuery)) {
    $agenda[] = $a;
}

$dokumentasi = [];
$dokQuery = pg_query($koneksi, "SELECT * FROM dokumentasi ORDER BY dokumentasi_id DESC");

if (!$dokQuery) {
    die("Query dokumentasi gagal: " . pg_last_error($koneksi));
}

while ($d = pg_fetch_assoc($dokQuery)) {
    $dokumentasi[] = $d;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <button class="scroll-btn left" onclick="scrollAgenda(-1)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="agenda-container">

            <?php if (count($agenda) === 0): ?>
                <p class="empty-text">Belum ada agenda.</p>
            <?php endif; ?>

            <?php foreach ($agenda as $ag): ?>
                <div class="agenda-card">
                    <h3><?= htmlspecialchars($ag['judul']); ?></h3>
                    <p><?= htmlspecialchars($ag['deskripsi']); ?></p>
                    <ul>
                        <li>Tanggal: <?= htmlspecialchars($ag['hari_tgl']); ?></li>
                        <li>Kegiatan oleh Lab NCS</li>
                    </ul>
                </div>
            <?php endforeach; ?>

        </div>

        <button class="scroll-btn right" onclick="scrollAgenda(1)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

    </div>
</section>

<section class="dokumentasi-section">
    <h2 class="section-title">Dokumentasi</h2>
    <div class="dokumentasi-container">

        <?php if (count($dokumentasi) === 0): ?>
            <p class="empty-text">Belum ada dokumentasi.</p>
        <?php endif; ?>

        <?php foreach ($dokumentasi as $dok): ?>
            <div class="dokumentasi-card">

                <div class="dokumentasi-image">
                    <img src="assets/img/<?= htmlspecialchars($dok['media_path']); ?>" 
                         alt="<?= htmlspecialchars($dok['judul']); ?>">
                </div>

                <div class="dokumentasi-info">
                    <h3><?= htmlspecialchars($dok['judul']); ?></h3>
                    <p>Dokumentasi kegiatan di Network & Cyber Security Lab</p>
                </div>

            </div>
        <?php endforeach; ?>

    </div>

    <div class="pagination">
        <button class="pagination-btn active">1</button>
        <button class="pagination-btn">2</button>
        <button class="pagination-btn">3</button>
    </div>
</section>

<div id="footer-placeholder"></div>
<script src="assets/js/footer.js"></script>
<script>
function scrollAgenda(direction) {
    const container = document.querySelector('.agenda-container');
    const cardWidth = container.querySelector('.agenda-card').offsetWidth + 20;
    container.scrollLeft += direction * cardWidth;
}
</script>
</body>
</html>