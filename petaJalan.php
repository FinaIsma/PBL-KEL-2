<?php
session_start();
include("koneksi.php");

// Ambil semua data peta jalan dari PostgreSQL
$peta = [];
$query = "SELECT * FROM peta_jalan ORDER BY tahun ASC";
$res = pg_query($koneksi, $query);

if (!$res) {
    die("Query gagal: " . pg_last_error($koneksi));
}

while ($row = pg_fetch_assoc($res)) {
    $peta[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalan - Network & Cyber Security Lab</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/petaJalan.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Navbar -->
    <div id="navbar-placeholder"></div>
    <script src="assets/js/navbar.js"></script>

    <main class="content-wrapper">

        <section class="hero-section">
            <h1 class="peta-jalan-title">Peta Jalan</h1>
        </section>

        <section class="timeline-section">
            <div class="aura-left-middle"></div>
            <div class="aura-bottom"></div>

            <div class="timeline-container">
                <div class="timeline-circle-top"></div>

                <?php 
                $index = 0;
                foreach ($peta as $row): 
                    $posisi = ($index % 2 == 0) ? "left" : "right"; 
                ?>
                    <div class="timeline-item <?= $posisi ?>">
                        <div class="timeline-card">

                            <div class="timeline-icon">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>

                            <h3><?= htmlspecialchars($row['tahun']) ?> – <?= htmlspecialchars($row['judul']) ?></h3>

                            <p><?= htmlspecialchars($row['deskripsi']) ?></p>

                            <div class="lihat-file">
                                <a class="btn file-btn"
                                    href="uploads/<?= htmlspecialchars($row['file_path']) ?>"
                                    target="_blank">
                                    <i class="fa-solid fa-download"></i> Lihat File
                                </a>
                            </div>

                        </div>
                    </div>
                <?php 
                $index++;
                endforeach; 
                ?>

                <div class="timeline-end-circle"></div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>

    <script>
        window.userRole = "<?= $_SESSION['role'] ?? 'user' ?>";
    </script>

    <script type="module" src="assets/js/main.js"></script>

</body>
</html>