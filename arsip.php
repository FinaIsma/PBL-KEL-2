<?php
require_once __DIR__ . "/backend/config.php";

// ==== PAGINATION SETTING ====
$limit  = 2;
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ===== BUILD QUERY DINAMIS =====
$where  = [];
$params = [];

// Filter kategori
if (!empty($_GET['kategori'])) {
    $where[] = "kategori = :kategori";
    $params[':kategori'] = $_GET['kategori'];
}

// Search
if (!empty($_GET['search'])) {
    $where[] = "(judul ILIKE :search OR deskripsi ILIKE :search)";
    $params[':search'] = "%" . $_GET['search'] . "%";
}

// Gabungkan WHERE
$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

// QUERY TOTAL DATA
$countQuery = "SELECT COUNT(*) FROM arsip $whereSQL";
$stmtCount  = $db->prepare($countQuery);
$stmtCount->execute($params);

$totalData  = $stmtCount->fetchColumn();
$totalPages = ceil($totalData / $limit);

// QUERY DATA
$query = "
    SELECT * FROM arsip
    $whereSQL
    ORDER BY tanggal DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $db->prepare($query);

// bind parameter filter
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}

// bind limit & offset (HARUS integer)
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/arsip.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- PDF.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <title>Arsip</title>
</head>

<body>

<!-- NAVBAR -->
<div id="navbar-placeholder"></div>
<script src="assets/js/navbar.js"></script>

<header class="arsip-hero">
    <h1>Arsip</h1>
</header>

<main class="wrapper">
<section class="section" id="arsip">
<div class="arsip-container">

    <!-- FILTER + SEARCH -->
    <div class="arsip-controls mb-5">
        <div class="d-flex gap-2">
            <a href="arsip.php" class="btn-filter active">Semua</a>
            <a href="penelitian.php?kategori=Penelitian" class="btn-filter">Penelitian</a>
            <a href="arsip.php?kategori=Pengabdian" class="btn-filter">Pengabdian</a>
        </div>

        <div class="search-box">
            <form method="GET">
                <input
                    type="text"
                    name="search"
                    class="input search-input"
                    placeholder="Search"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        </div>
    </div>

    <!-- LIST -->
    <div class="arsip-list">

        <?php if ($totalData == 0): ?>
            <p>Tidak ada data arsip ditemukan.</p>
        <?php endif; ?>

        <?php foreach ($result as $row): ?>
        <div class="arsip-card">
            <div class="arsip-content">

                <div class="arsip-text">
                    <a href="arsipDetail.php?id=<?= $row['arsip_id'] ?>" class="arsip-title mb-3">
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
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a
                href="arsip.php?page=<?= $i ?>
                    <?= !empty($_GET['kategori']) ? '&kategori='.$_GET['kategori'] : '' ?>
                    <?= !empty($_GET['search']) ? '&search='.$_GET['search'] : '' ?>"
                class="page-btn <?= ($i == $page ? 'active' : '') ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

</div>
</section>
</main>

<div id="footer-placeholder"></div>
<script src="assets/js/footer.js"></script>

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
