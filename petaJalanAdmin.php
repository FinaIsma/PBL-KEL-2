<?php
session_start();
include("koneksi.php");

// Search
$search = $_GET['search'] ?? '';

// Query search
if ($search != '') {
    $query = "
        SELECT * FROM peta_jalan
        WHERE judul ILIKE '%$search%'
           OR deskripsi ILIKE '%$search%'
           OR CAST(tahun AS TEXT) ILIKE '%$search%'
        ORDER BY tahun ASC
    ";
} else {
    $query = "SELECT * FROM peta_jalan ORDER BY tahun ASC";
}

$peta = [];
$res = pg_query($koneksi, $query);

while ($row = pg_fetch_assoc($res)) {
    $peta[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalan Admin - Network & Cyber Security Lab</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/css/pages/petaJalanAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div id="header-placeholder"></div>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div id="sidebar-placeholder"></div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="content">

            <!-- HERO TITLE -->
            <section class="hero-section-admin">
                <h1>Peta Jalan</h1>
            </section>

            <!-- SEARCH BAR -->
            <form method="GET" class="peta-search-form">
                <div class="search-box-peta">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" name="search" placeholder="Cari judul, tahun, atau deskripsi..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
            </form>

            <!-- TIMELINE SECTION -->
            <section class="timeline-section-admin">

                <div class="timeline-container-admin">

                    <div class="timeline-circle-top-admin"></div>

                    <?php 
                    $index = 0;
                    foreach ($peta as $row): 
                        $posisi = ($index % 2 == 0) ? "left" : "right"; 
                    ?>
                        <div class="timeline-item-admin <?= $posisi ?>">
                            <div class="timeline-dot-small"></div>

                            <div class="timeline-card-admin">
                                <div class="timeline-icon-admin">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>

                                <h3><?= htmlspecialchars($row['judul']) ?> – <?= htmlspecialchars($row['tahun']) ?></h3>

                                <p><?= htmlspecialchars($row['deskripsi']) ?></p>

                                <a class="btn-lihat" href="uploads/<?= $row['file_path'] ?>" target="_blank">
                                    <i class="fa-solid fa-download"></i> Lihat File
                                </a>
                            </div>
                        </div>
                    <?php 
                        $index++;
                    endforeach; 
                    ?>

                </div>

                <!-- BUTTON KELOLA -->
                <div class="kelola-button-wrapper">
                    <a href="petaJalanTabel.php" class="kelola-button">Kelola</a>
                </div>

            </section>
        </main>
    </div>

    <script src="assets/js/headerSidebar.js"></script>

</body>
</html>
