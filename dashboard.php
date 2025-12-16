<?php
session_start();
require_once "backend/config.php";
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// AMBIL DATA PENGELOLA LAB
try {
    $stmt = $db->prepare("SELECT * FROM pengelola_lab ORDER BY pengelola_id ASC");
    $stmt->execute();
    $pengelola = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error DB: " . $e->getMessage());
}

// HITUNG JUMLAH DATA
try {
    // Pengelola Lab
    $count_pengelola = $db->query("SELECT COUNT(*) FROM pengelola_lab")->fetchColumn();

    // Bidang Fokus
    $count_fokus = $db->query("SELECT COUNT(*) FROM bidang_fokus")->fetchColumn();

    // Layanan
    $count_layanan = $db->query("SELECT COUNT(*) FROM layanan")->fetchColumn();

    // Sarana Prasarana
    $count_sarpras = $db->query("SELECT COUNT(*) FROM sarana_prasarana")->fetchColumn();

    // Agenda
    $count_agenda = $db->query("SELECT COUNT(*) FROM agenda")->fetchColumn();

    // Arsip
    $count_arsip = $db->query("SELECT COUNT(*) FROM arsip")->fetchColumn();

} catch (PDOException $e) {
    die("Error DB COUNT: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="assets/css/pages/dashboard.css">

    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- HEADER -->
    <div id="header"></div>

    <!-- IMPORT SIDEBAR -->
    <div id="sidebar"></div>
                <section class="aura-bg">
                <img src="assets/img/aura.png" alt="" class="aura aura-1">
                <img src="assets/img/aura.png" alt="" class="aura aura-2">
            </section>
        <main class="content">
            <h1 class="title-page">Dashboard</h1>

            <section class="cards">
                <!-- Pengelola Lab -->
                <div class="card-item">
                    <div class="icon blue"><i class="fa-solid fa-user-gear"></i></div>
                    <div>
                        <h3>Pengelola Lab</h3>
                        <p>Jumlah: <?= $count_pengelola ?></p>
                    </div>
                    <a href="tabelPengelola.php" class="card-btn">→</a>
                </div>

                <!-- Bidang Fokus -->
                <div class="card-item">
                    <div class="icon navy"><i class="fa-solid fa-bullseye"></i></div>
                    <div>
                        <h3>Bidang Fokus</h3>
                        <p>Jumlah: <?= $count_fokus ?></p>
                    </div>
                    <a href="tabelBidang.php" class="card-btn">→</a>
                </div>

                <!-- Layanan -->
                <div class="card-item">
                    <div class="icon box"><i class="fa-solid fa-hand-holding-hand"></i></div>
                    <div>
                        <h3>Layanan</h3>
                        <p>Jumlah: <?= $count_layanan ?></p>
                    </div>
                    <a href="tabelLayanan.php" class="card-btn">→</a>
                </div>

                <!-- Sarana Prasarana -->
                <div class="card-item">
                    <div class="icon facility"><i class="fa-solid fa-building"></i></div>
                    <div>
                        <h3>Sarana Prasarana</h3>
                        <p>Jumlah: <?= $count_sarpras ?></p>
                    </div>
                    <a href="tabelSarpras.php" class="card-btn">→</a>
                </div>

                <!-- Agenda -->
                <div class="card-item">
                    <div class="icon agenda"><i class="fa-solid fa-calendar-check"></i></div>
                    <div>
                        <h3>Agenda</h3>
                        <p>Jumlah: <?= $count_agenda ?></p>
                    </div>
                    <a href="tabelAgenda.php" class="card-btn">→</a>
                </div>

                <!-- Arsip -->
                <div class="card-item">
                    <div class="icon archive"><i class="fa-solid fa-box-archive"></i></div>
                    <div>
                        <h3>Arsip</h3>
                        <p>Jumlah: <?= $count_arsip ?></p>
                    </div>
                    <a href="arsipAdmin.php" class="card-btn">→</a>
                </div>
            </section>

            <section class="table-section">
                <div class="section-head">
                    <h2>Pengelola Lab</h2>
                    <div class="search-box">
                        <input type="text" placeholder="Search" />
                    </div>
                </div>
                <img src="assets/img/aura.png" alt="" class="aura aura-1">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Jabatan</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php foreach ($pengelola as $row): ?>
        <tr>
            <td><?= $row['pengelola_id'] ?></td>

            <td>
                <?php if (!empty($row['foto'])): ?>
                    <img src="uploads/pengelola/<?=$row['foto']?>" 
                         alt="Foto" >
                         <!-- style="width:55px; height:55px; object-fit:cover; border-radius:6px;"-->
                <?php else: ?>
                    <div class="img-placeholder"></div>
                <?php endif; ?>
            </td>

            <td><?= $row['jabatan'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['kontak'] ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>
                </table>

                <div class="button-area">
                    <a href="tabelPengelola.php">
                        <button class="kelola-btn">Kelola</button>
                    </a>
                </div>
            </section>
        </main>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/sidebarHeader.js"></script>
</body>
</html>
