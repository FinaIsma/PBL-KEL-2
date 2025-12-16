<?php
include("db.php");

// ====== AMBIL DATA PENGELOLA ======
$pengelola = [];
$res = pg_query($conn, "SELECT * FROM pengelola_lab ORDER BY pengelola_id ASC");
while ($r = pg_fetch_assoc($res)) {
    $pengelola[] = $r;
}

// ====== AMBIL DATA PROFIL ======
$profil = [
    'sejarah' => null,
    'visi'    => null,
    'misi'    => []
];

$q = pg_query($conn, "SELECT * FROM profil ORDER BY profil_id ASC");
while ($p = pg_fetch_assoc($q)) {
    $kategori = strtolower(trim($p['kategori']));

    if ($kategori === 'misi') {
        $profil['misi'][] = $p; // kumpulkan semua misi
    } else {
        $profil[$kategori] = $p; // sejarah & visi
    }
}

$tentangKami = $profil['sejarah']['isi'] ?? "";
$visi        = $profil['visi']['isi'] ?? "";
$misiList    = $profil['misi']; // ARRAY
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <link rel="stylesheet" href="assets/css/pages/profil.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<div id="navbar-placeholder"></div>
<script src="assets/js/navbar.js"></script>

<!-- TENTANG KAMI -->
<section class="tentangKami">
    <div class="tentangKami-container">
        <div class="tentangKami-left">
            <h1>Tentang</h1>
            <h1>Kami</h1>
        </div>

        <div class="tentangKami-right">
            <p><?= nl2br(htmlspecialchars($tentangKami)) ?></p>

            <div class="btn-link">
                <a href="https://sinta.kemdikbud.go.id" target="_blank" class="btn">SINTA</a>
                <a href="#" target="_blank" class="btn">SIMTA JTI</a>
                <a href="https://jti.polinema.ac.id" target="_blank" class="btn">JTI Polinema</a> 
            </div>
        </div>
    </div>
</section>

<!-- VISI & MISI -->
<section class="visi-misi">
    <h2 class="vismis-title text-center mb-4">Visi Misi</h2>
    <hr class="vismis-line">

    <div class="card d-flex gap-5">
        <div class="card-visi">
            <h3 class="text-center">Visi</h3>
            <p><?= nl2br(htmlspecialchars($visi)) ?></p>
        </div>

        <div class="card-misi">
            <h3 class="text-center">Misi</h3>
            <ul>
                <?php foreach ($misiList as $misi): ?>
                    <li><?= htmlspecialchars($misi['isi']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>

<!-- PENGELOLA LAB -->
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

<!-- FOOTER -->
<div id="footer-placeholder"></div>
<script src="assets/js/footer.js"></script>

<script>
    window.userRole = '<?= $_SESSION['role'] ?? "user" ?>';
</script>

<script type="module" src="assets/js/main.js"></script>
</body>
</html>
