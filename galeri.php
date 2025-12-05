<?php
session_start();
include("koneksi.php");

$perPage = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$qDok = pg_query($koneksi, "
    SELECT * FROM dokumentasi
    ORDER BY dokumentasi_id DESC
    LIMIT $perPage OFFSET $offset
");

$dokumentasi = [];
while ($row = pg_fetch_assoc($qDok)) {
    $dokumentasi[] = $row;
}

$qTotal = pg_query($koneksi, "SELECT COUNT(*) AS total FROM dokumentasi");
$totalPages = ceil(pg_fetch_assoc($qTotal)['total'] / $perPage);
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
            <button class="scroll-btn left">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="agenda-container">
                <div class="agenda-card">
                    <h3>Nama Kegiatan</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <ul>
                        <li>Poin ini unus, incididique sed accusam elementum</li>
                        <li>Gravida sit blandit senarum sit</li>
                    </ul>
                </div>

                <div class="agenda-card">
                    <h3>Nama Kegiatan</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <ul>
                        <li>Poin ini unus, incididique sed accusam elementum</li>
                        <li>Gravida sit blandit senarum sit</li>
                    </ul>
                </div>

                <div class="agenda-card">
                    <h3>Nama Kegiatan</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <ul>
                        <li>Poin ini unus, incididique sed accusam elementum</li>
                        <li>Gravida sit blandit senarum sit</li>
                    </ul>
                </div>
            </div>

            <button class="scroll-btn right">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <section class="dokumentasi-section">
        <h2 class="section-title">Dokumentasi</h2>

        <div class="dokumentasi-container">

            <?php foreach ($dokumentasi as $dok): ?>
            <div class="dokumentasi-card">
                <div class="dokumentasi-image">
                    <img src="assets/img/<?= htmlspecialchars($dok['media_path']); ?>" alt="">
                </div>
                <div class="dokumentasi-info">
                    <h3><?= htmlspecialchars($dok['judul']); ?></h3>
                    <p><?= htmlspecialchars($dok['deskripsi']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a 
                    href="galeri.php?page=<?= $i ?>" 
                    class="pagination-btn <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

    </section>

    <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>

    <script>
        const agendaContainer = document.querySelector('.agenda-container');

        document.querySelector('.scroll-btn.left').addEventListener('click', function() {
            agendaContainer.scrollBy({ left: -300, behavior: 'smooth' });
        });

        document.querySelector('.scroll-btn.right').addEventListener('click', function() {
            agendaContainer.scrollBy({ left: 300, behavior: 'smooth' });
        });
    </script>

</body>
</html>