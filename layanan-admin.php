<?php
include("db.php");

// Ambil data Sarpras
$result = pg_query($conn, "SELECT * FROM sarana_prasarana ORDER BY sarpras_id ASC");
$sarpras = [];
while ($row = pg_fetch_assoc($result)) {
    $sarpras[] = $row;
}

// Ambil data Layanan
$layanan = [];
$res = pg_query($conn, "SELECT * FROM layanan ORDER BY layanan_id ASC");
while ($row = pg_fetch_assoc($res)) {
    $layanan[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Admin</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/layanan-admin.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <!-- Header -->
    <div id="header-placeholder"></div>

    <div class="layout">
        
        <!-- Sidebar -->
        <aside class="sidebar">
            <div id="sidebar-placeholder"></div>
        </aside>

        <!-- Main Content -->
        <main class="content">

            <!-- Hero / Banner -->
            <section class="layanan-foto">
                <div class="layanan-container">
                    <h1>Layanan</h1>
                </div>
            </section>

            <!-- Sarpras -->
            <section class="Sarpras">
                <h2>Sarana Prasarana</h2>
                <hr class="sarpras-line">

                <div class="Sarpras-container" data-sarpras-container>

                    <?php foreach ($sarpras as $index => $item): ?>
                        <div class="Sarpras-card" data-page="<?= ceil(($index + 1) / 6) ?>">

                            <?php if ($item['media_path'] && file_exists($item['media_path'])): ?>
                                <img class="Sarpras-foto" src="<?= htmlspecialchars($item['media_path']) ?>" alt="<?= htmlspecialchars($item['judul']) ?>">
                            <?php else: ?>
                                <div class="Sarpras-dummy"></div>
                            <?php endif; ?>

                            <h3><?= htmlspecialchars($item['judul']) ?></h3>

                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="button-container" data-button-container></div>

                <a href="tabelSarpras.php" class="btn-simpan2">Kelola</a>
            </section>

            <!-- Layanan -->
            <section class="layanan-title">
                <h2>Layanan</h2>
                <hr class="sarpras-line">

                <div class="layanan-wrapper">
                    <?php foreach ($layanan as $item): ?>
                        <div class="layanan-card">
                            <h2 class="title-konsul"><?= htmlspecialchars($item['nama']) ?></h2>
                            <p><?= htmlspecialchars($item['deskripsi']) ?></p>
                            <a href="https://wa.me/" target="_blank" class="btn-box">Lanjutkan</a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <a href="tabelLayanan.php" class="btn-simpan2">Kelola</a>
            </section>

        </main>
    </div>

    <script src="assets/js/headerSidebar.js"></script>
    <script>
        window.allSarpras = <?= json_encode($sarpras) ?>;
    </script>
    <script type="module" src="assets/js/main.js"></script>

</body>
</html>
