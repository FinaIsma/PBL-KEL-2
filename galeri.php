<?php
require_once __DIR__ . "/backend/config.php";

// ===== PAGINATION (6 ITEM / HALAMAN) =====
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6; // 2 row x 3 gambar
$start = ($page - 1) * $limit;

try {
    // ===== AMBIL AGENDA =====
    $agendaStmt = $db->prepare("
        SELECT agenda_id, hari_tgl, judul, deskripsi
        FROM agenda
        ORDER BY hari_tgl ASC
    ");
    $agendaStmt->execute();
    $agendaData = $agendaStmt->fetchAll(PDO::FETCH_ASSOC);

    // ===== AMBIL DOKUMENTASI (PAGINATION) =====
    $dokStmt = $db->prepare("
        SELECT dokumentasi_id, judul, media_path
        FROM dokumentasi
        ORDER BY dokumentasi_id ASC
        OFFSET :start LIMIT :limit
    ");
    $dokStmt->bindValue(':start', $start, PDO::PARAM_INT);
    $dokStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $dokStmt->execute();
    $dokumentasiData = $dokStmt->fetchAll(PDO::FETCH_ASSOC);

    // ===== TOTAL HALAMAN =====
    $countStmt = $db->query("SELECT COUNT(*) FROM dokumentasi");
    $totalItems = $countStmt->fetchColumn();
    $totalPages = ceil($totalItems / $limit);

} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
            <button class="scroll-btn left"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="agenda-container">
                <?php foreach ($agendaData as $row): ?>
                    <div class="agenda-card">
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                        <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                        <p><small>Tanggal: <?= htmlspecialchars($row['hari_tgl']) ?></small></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="scroll-btn right"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

    <section class="dokumentasi-section" id="dokumentasi">
        <h2 class="section-title">Dokumentasi</h2>
        <div class="dokumentasi-container">
            <?php foreach ($dokumentasiData as $row): ?>
                <div class="dokumentasi-card">
                    <div class="dokumentasi-image"
                         style="background-image:url('<?= htmlspecialchars($row['media_path']) ?>')"></div>
                    <div class="dokumentasi-info">
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="galeri.php?page=<?= $i ?>#dokumentasi"
                   class="pagination-btn <?= ($i == $page ? 'active' : '') ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </section>

    <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>
    <script src="assets/js/galeri.js"></script>
</body>
</html>
