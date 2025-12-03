<?php
include("db.php"); // koneksi database

// ambil semua data sarpras
$result = pg_query($conn, "SELECT * FROM sarana_prasarana ORDER BY sarpras_id ASC");
$sarpras = [];
while($row = pg_fetch_assoc($result)) {
    $sarpras[] = $row;
}

$layanan = [];
$res = pg_query($conn, "SELECT * FROM layanan ORDER BY layanan_id ASC");
while($row = pg_fetch_assoc($res)) {
    $layanan[] = $row;
}
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
    <link rel="stylesheet" href="assets/css/pages/layanan.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <!-- <link rel="stylesheet" href="assets/css/pages/footer.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    
    <!-- Header -->
    <div id="navbar-placeholder"></div>
    <script src="assets/js/navbar.js"></script>

            <section class="layanan-foto">
            <div class="layanan-container">
                <h1 class="">Layanan</h1>
            </div>
        </section>


        <section class="Sarpras">
    <h2>Sarana Prasarana</h2>
    <hr class="sarpras-line">

    <!-- Container kartu -->
    <div class="Sarpras-container" data-sarpras-container>
        <?php foreach ($sarpras as $index => $item): ?>
            <div class="Sarpras-card" data-page="<?= ceil(($index+1)/6) ?>">
                <?php if($item['media_path'] && file_exists($item['media_path'])): ?>
                    <img class="Sarpras-foto" src="<?= htmlspecialchars($item['media_path']) ?>" alt="<?= htmlspecialchars($item['judul']) ?>">
                <?php else: ?>
                    <div class="Sarpras-dummy"></div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($item['judul']) ?></h3>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Tombol pagination, JS yang buat -->
    <div data-button-container class="button-container"></div>
</section>

        <section class="layanan-title">
    <h2>Layanan</h2>
    <hr class="sarpras-line">

    <div class="layanan-wrapper">
        <?php foreach($layanan as $item): ?>
            <div class="layanan-card">
                <h2 class="title-konsul"><?= htmlspecialchars($item['nama']) ?></h2>
                <p><?= htmlspecialchars($item['deskripsi']) ?></p>
                <a href="https://wa.me/" target="_blank" class="btn-box">Lanjutkan</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

        </main>
    </div>

   <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>
    <script>
    window.allSarpras = <?= json_encode($sarpras) ?>;
</script>
    <script type="module" src="assets/js/main.js"></script>
</body>
</html>