<?php
session_start();
include("db.php");

// ====== AMBIL DATA PROFIL ======
$pengelola = [];
$res = pg_query($conn, "SELECT * FROM pengelola_lab ORDER BY pengelola_id ASC");
while ($r = pg_fetch_assoc($res)) {
    $pengelola[] = $r;
}

$profil = [];
$q = pg_query($conn, "SELECT * FROM profil ORDER BY profil_id ASC");
while ($p = pg_fetch_assoc($q)) {
    $key = strtolower(trim($p['kategori']));
    $profil[$key] = $p;
}

$tentangKami = $profil['sejarah']['isi'] ?? "";
$visi        = $profil['visi']['isi'] ?? "";
$misi        = $profil['misi']['isi'] ?? "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami</title>

    <!-- CSS Base & Utilities -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <!-- Halaman spesifik -->
    <link rel="stylesheet" href="assets/css/pages/profil.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- Navbar -->
    <div id="navbar-placeholder"></div>
    <script src="assets/js/navbar.js"></script>

    <!-- Tentang Kami -->
    <section class="tentangKami">
        <div class="tentangKami-container">
            <div class="tentangKami-left">
                <h1>Tentang</h1>
                <h1>Kami</h1>
            </div>

            <div class="tentangKami-right">
                <p>
                    <?= nl2br(htmlspecialchars($tentangKami)) ?>
                </p>

                <div class="btn-link">
                    <a href="https://sinta.kemdikbud.go.id" target="_blank" class="btn">SINTA</a>
                    <a href="" target="_blank" class="btn">SIMTA JTI</a>
                    <a href="https://jti.polinema.ac.id" target="_blank" class="btn">JTI Polinema</a> 
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="visi-misi">
        <h2 class="vismis-title text-center mb-4">Visi Misi</h2>
        <hr class="vismis-line">

        <div class="card d-flex gap-5">
            <div class="card-visi">
                <h3 class="text-center">Visi</h3>
                <p>
                    <?= nl2br(htmlspecialchars($visi)) ?>
                </p>
            </div>

            <div class="card-misi">
                <h3 class="text-center">Misi</h3>
                <ul>
                    <?php 
                    $misiList = explode("\n", $misi);
                    foreach ($misiList as $item):
                        if (trim($item) == "") continue;
                    ?>
                        <li><?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

    <!-- Pengelola Lab -->
    <section class="pengelolaLab">
        <h2 class="pengelolaLab-title text-center">Pengelola Lab</h2>
        <hr class="pengelola-line">

        <div class="slider-container">
            <button data-arrow="left" class="arrow arrow-left">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="pengelolaLab-card" data-pengelola-slider data-card-container>
                <?php foreach($pengelola as $row): ?>
                    <div class="card-name" data-card>
                        <div class="foto" style="background-image:url('<?= htmlspecialchars($row['foto']) ?>');"></div>
                        <div class="card-info">
                            <p class="card-header"><?= htmlspecialchars($row['nama']) ?></p>
                            <p class="card-body"><?= htmlspecialchars($row['jabatan']) ?></p>
                            <p class="card-contact"><?= htmlspecialchars($row['kontak']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button data-arrow="right" class="arrow arrow-right">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <!-- Footer -->
    <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>

    <script>
        window.userRole = '<?= $_SESSION['role'] ?? "user" ?>';
    </script>

    <script type="module" src="assets/js/main.js"></script>
</body>
</html>
