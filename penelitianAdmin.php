<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . "/backend/config.php";

// ==== CONFIG PAGINATION ====
$limit  = 2;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ==== FILTER (Kategori wajib Penelitian) ====
$where  = ["kategori = :kategori"];
$params = [':kategori' => 'Penelitian'];

// Search
if (!empty($_GET['search'])) {
    $where[] = "(judul ILIKE :search OR deskripsi ILIKE :search)";
    $params[':search'] = "%" . $_GET['search'] . "%";
}

$whereSQL = "WHERE " . implode(" AND ", $where);

try {
    // ==== HITUNG TOTAL DATA ====
    $countStmt = $db->prepare("SELECT COUNT(*) FROM arsip $whereSQL");
    $countStmt->execute($params);
    $totalData  = $countStmt->fetchColumn();
    $totalPages = ceil($totalData / $limit);

    // ==== AMBIL DATA ====
    $stmt = $db->prepare("
        SELECT * FROM arsip
        $whereSQL
        ORDER BY tanggal DESC
        LIMIT :limit OFFSET :offset
    ");

    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/arsipAdmin.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- PDF.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <title>Kelola Arsip</title>
</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="wrapper">
<section class="section" id="arsip">

    <div class="arsip-title-header">
        <h1>Arsip</h1>
    </div>

    <!-- FILTER + SEARCH -->
    <div class="arsip-controls mb-5">
        <div class="d-flex gap-2">
            <a href="arsipAdmin.php" class="btn-filter">Semua</a>
            <a href="penelitianAdmin.php" class="btn-filter active">Penelitian</a>
            <a href="pengabdianAdmin.php" class="btn-filter">Pengabdian</a>
        </div>

        <div class="search-box">
            <form method="GET">
                <input
                    type="text"
                    class="input search-input"
                    placeholder="Search"
                    name="search"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                >
            </form>
        </div>
    </div>

    <!-- LIST -->
    <div class="arsip-list">

        <?php if ($totalData == 0): ?>
            <p>Tidak ada arsip kategori Penelitian ditemukan.</p>
        <?php endif; ?>
        
        <?php foreach ($result as $row): ?>
        <div class="arsip-card" data-category="penelitian">
            <div class="arsip-content">

                <div class="arsip-text">
                    <a href="arsipDetailAdmin.php?id=<?= $row['arsip_id'] ?>" class="arsip-title mb-3">
                        <?= htmlspecialchars($row['judul']); ?>
                    </a>

                    <p class="arsip-desc mb-4">
                        <?= htmlspecialchars($row['deskripsi']); ?>
                    </p>

                    <div class="arsip-meta">
                        <div class="meta-item">Penulis: <?= htmlspecialchars($row['penulis']); ?></div>
                        <div class="meta-item">Tanggal: <?= date("d M Y", strtotime($row['tanggal'])); ?></div>
                        <div class="meta-item">Kategori: <?= htmlspecialchars($row['kategori']); ?></div>
                    </div>
                </div>

                <div class="arsip-side">
                    <!-- AUTO PDF THUMBNAIL -->
                    <div class="arsip-thumbnail">
                        <canvas
                            class="pdf-thumb"
                            data-pdf="upload/<?= htmlspecialchars($row['file_path']); ?>">
                        </canvas>
                    </div>

                    <a href="upload/<?= htmlspecialchars($row['file_path']); ?>"
                       class="btn-download"
                       download>
                        <span>⬇</span> Unduh
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a
                href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                class="page-btn <?= $page == $i ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <div class="kelola-wrapper">
        <a href="arsipTabel.php" class="btn-kelola">Kelola</a>
    </div>

</section>
</main>

<script src="assets/js/sidebarHeader.js"></script>
<script src="assets/js/downloadArsip.js"></script>
<script type="module" src="assets/js/main.js"></script>

<!-- SCRIPT GENERATE PDF THUMBNAIL -->
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

    document.querySelectorAll(".pdf-thumb").forEach(canvas => {
        const pdfUrl = canvas.dataset.pdf;

        pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 0.35 });
                const context  = canvas.getContext("2d");

                canvas.width  = viewport.width;
                canvas.height = viewport.height;

                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        }).catch(err => {
            console.error("PDF error:", err);
        });
    });
</script>

<style>
    .navbar {
        background-color: white !important;
    }

    .arsip-thumbnail {
        width: 80px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .pdf-thumb {
        width: 100%;
        height: auto;
        display: block;
        background: #f1f1f1;
        border-radius: 4px;
    }
</style>

</body>
</html>
