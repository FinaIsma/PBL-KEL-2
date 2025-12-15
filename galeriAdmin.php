<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/backend/config.php";

try {
    /* ===================== AGENDA ===================== */
    $agendaStmt = $db->prepare("
        SELECT agenda_id, hari_tgl, judul, deskripsi
        FROM agenda
        ORDER BY hari_tgl DESC
    ");
    $agendaStmt->execute();
    $agendaData = $agendaStmt->fetchAll(PDO::FETCH_ASSOC);

    /* ===================== PAGINATION DOKUMENTASI ===================== */
    $limit  = 6;
    $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page   = max($page, 1);
    $offset = ($page - 1) * $limit;

    $totalStmt = $db->query("SELECT COUNT(*) FROM dokumentasi");
    $totalData = (int) $totalStmt->fetchColumn();
    $totalPage = ceil($totalData / $limit);

    $dokStmt = $db->prepare("
        SELECT dokumentasi_id, judul, media_path
        FROM dokumentasi
        ORDER BY dokumentasi_id DESC
        LIMIT :limit OFFSET :offset
    ");
    $dokStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $dokStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $dokStmt->execute();
    $dokData = $dokStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Galeri Admin</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="assets/css/pages/galeriAdmin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <!-- ===================== AGENDA ===================== -->
    <section class="hero-section-admin">
        <h1>Galeri</h1>
    </section>

    <section class="agenda-section-admin">
        <h2 class="section-title">Agenda Mendatang</h2>

        <div class="agenda-wrapper">
            <button class="agenda-nav agenda-prev">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="agenda-container">
                <?php foreach ($agendaData as $row): ?>
                    <div class="agenda-card">
                        <h3><?= htmlspecialchars($row['judul']) ?></h3>
                        <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                        <ul>
                            <li>Tanggal: <?= date("d M Y", strtotime($row['hari_tgl'])) ?></li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="agenda-nav agenda-next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div class="agenda-button-wrapper">
            <a href="tabelAgenda.php">
                <button class="kelola-button">Kelola</button>
            </a>
        </div>
    </section>

    <!-- ===================== DOKUMENTASI ===================== -->
    <section class="dokumentasi-section-admin">
        <h2 class="section-title">Dokumentasi</h2>

        <div class="dokumentasi-container">
            <?php foreach ($dokData as $dok): ?>
                <div class="dokumentasi-card">
                    <div class="dokumentasi-image"
                         style="background-image:url('<?= htmlspecialchars($dok['media_path']) ?>');">
                    </div>
                    <div class="dokumentasi-info">
                        <h3><?= htmlspecialchars($dok['judul']) ?></h3>
                        <p>Dokumentasi kegiatan terbaru</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ===================== PAGINATION ===================== -->
        <?php if ($totalPage > 1): ?>
        <div class="pagination">

            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="pagination-btn">«</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                <a href="?page=<?= $i ?>"
                   class="pagination-btn <?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPage): ?>
                <a href="?page=<?= $page + 1 ?>" class="pagination-btn">»</a>
            <?php endif; ?>

        </div>
        <?php endif; ?>

        <div class="dokumentasi-button-wrapper">
            <a href="tabelDokumentasi.php">
                <button class="kelola-button">Kelola</button>
            </a>
        </div>
    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>
<script>
const agendaPrev = document.querySelector('.agenda-prev');
const agendaNext = document.querySelector('.agenda-next');
const agendaContainer = document.querySelector('.agenda-container');

if (agendaPrev && agendaNext && agendaContainer) {
    agendaPrev.addEventListener('click', () => {
        agendaContainer.scrollBy({ left: -350, behavior: 'smooth' });
    });
    agendaNext.addEventListener('click', () => {
        agendaContainer.scrollBy({ left: 350, behavior: 'smooth' });
    });
}
</script>

</body>
</html>
