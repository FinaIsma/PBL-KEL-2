<?php
include("db.php"); // pastikan koneksi sudah benar

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

$tentangKami = $profil['sejarah'] ?? ['isi' => ''];
$visi        = $profil['visi'] ?? ['isi' => ''];
$misi        = $profil['misi'] ?? ['isi' => ''];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- <link rel="stylesheet" href="assets/css/layout.css"> -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/profil-admin.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <!-- <link rel="stylesheet" href="assets/css/pages/footer.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    
    <!-- Header -->
    <div id="header-placeholder"></div>
    
    <!-- Layout dengan Sidebar -->
    <div class="layout">

        <aside class="sidebar">
            <div id="sidebar-placeholder"></div>
        </aside>
        <main class="content">

            <section class="tentangKami">
                <div class="tentangKami-container">
                    <div class="tentangKami-left">
                        <h1>Tentang</h1>
                        <div class="title-button">
                            <h1>Kami</h1>
                            <a href="tabelProfil.php"class="btn-edit-tenkam" data-edit="tentangKami" title="Edit Tentang Kami">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                        </div>
                    </div>

                    <div class="tentangKami-right">
                            <p>
                                <?= nl2br(htmlspecialchars($tentangKami['isi'])) ?>
                            </p>
                        

                        <div class="btn-link">
                            <a href="https://sinta.kemdikbud.go.id" target="_blank" class="btn">SINTA</a>
                            <a href="" target="_blank" class="btn">SIMTA JTI</a>
                            <a href="https://jti.polinema.ac.id" target="_blank" class="btn">JTI Polinema</a> 
                        </div>
                        <div class="btn-simpan-wrapper">
                            <a class="btn-simpan">Simpan</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="visi-misi">
                <div class="vismis-button">
                    <h2 class="vismis-title text-center mb-4">Visi Misi</h2>
                    <a href="tabelProfil.php" class="btn-edit-vismis" data-edit="visiMisi" title="Edit Visi Misi">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                </div>
                <hr class="vismis-line">

                <div class="card d-flex gap-5">
                    <div class="card-visi">
                        <h3 class="text-center">Visi</h3>
                        <p>
                           
                            <?= nl2br(htmlspecialchars($visi['isi'])) ?>
                        </p>

                        
                    </div>

                    <div class="card-misi">
                        <h3 class="text-center">Misi</h3>
                        <ul>
<?php 
$misiList = explode("\n", $misi['isi']);
foreach ($misiList as $item):
    if (trim($item) == "") continue;
?>
    <li><?= htmlspecialchars($item) ?></li>
<?php endforeach; ?>
</ul>

                    </div>
                </div>
                <a class="btn-simpan2">Simpan</a>
            </section>

            <section class="pengelolaLab">
    <h2 class="pengelolaLab-tittle text-center">Pengelola Lab</h2>
    <hr class="pengelola-line">

    <div class="slider-container ">
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
    <a href="tabelPengelola.php" class="btn-kelola">Kelola</a>
</section>
        </main>
    </div>

    <script src="assets/js/headerSidebar.js"></script>
    <script>
  // ambil role dari session, default admin kalau tidak ada
  window.userRole = '<?= $_SESSION['role'] ?? "admin" ?>';
</script>
<script type="module" src="assets/js/main.js"></script>
</body>
</html>