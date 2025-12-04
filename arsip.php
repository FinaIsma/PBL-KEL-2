<?php
include "koneksi.php";

// ==== PAGINATION SETTING ====
$limit = 2; // jumlah arsip per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ===== BUILD QUERY DASAR =====
$where = [];

// Filter kategori
if (!empty($_GET['kategori'])) {
    $kategori = pg_escape_string($conn, $_GET['kategori']);
    $where[] = "kategori = '$kategori'";
}

// Search
if (!empty($_GET['search'])) {
    $search = pg_escape_string($conn, $_GET['search']);
    $where[] = "(judul ILIKE '%$search%' OR deskripsi ILIKE '%$search%')";
}

// Gabungkan WHERE jika ada
$whereSQL = "";
if (count($where) > 0) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

// Query total data
$countQuery = "SELECT COUNT(*) AS total FROM arsip $whereSQL";
$countResult = pg_query($conn, $countQuery);
$totalData = pg_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalData / $limit);

// Query data dengan pagination
$query = "
    SELECT * FROM arsip
    $whereSQL
    ORDER BY tanggal DESC
    LIMIT $limit OFFSET $offset
";
$result = pg_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/arsip.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
                            value="<?= $_GET['search'] ?? '' ?>"
                        >
                    </form>
                </div>
            </div>

            <!-- LIST -->
            <div class="arsip-list">

                <?php if ($totalData == 0): ?>
                    <p>Tidak ada data arsip ditemukan.</p>
                <?php endif; ?>

                <?php while ($row = pg_fetch_assoc($result)): ?>
                <div class="arsip-card">
                    <div class="arsip-content">
                        
                        <div class="arsip-text">
                            <a href="arsipDetail.php?id=<?= $row['arsip_id'] ?>" class="arsip-title mb-3">
                                <?= $row['judul']; ?>
                            </a>

                            <p class="arsip-desc mb-4">
                                <?= $row['deskripsi']; ?>
                            </p>

                            <div class="arsip-meta">
                                <div class="meta-item">Penulis: <?= $row['penulis']; ?></div>
                                <div class="meta-item">
                                    Tanggal: <?= date("d M Y", strtotime($row['tanggal'])); ?>
                                </div>
                                <div class="meta-item">Kategori: <?= $row['kategori']; ?></div>
                            </div>
                        </div>

                        <div class="arsip-side">
                            <div class="arsip-thumbnail">
                                <img src="upload/<?= $row['thumbnail']; ?>" alt="">
                            </div>

                            <a href="upload/<?= $row['file_path']; ?>" 
                               class="btn-download" 
                               download>
                               <span>⬇</span> Unduh
                            </a>
                        </div>
                        
                    </div>
                </div>
                <?php endwhile; ?>

            </div>

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a 
                        href="arsip.php?page=<?= $i ?>
                            <?= !empty($_GET['kategori']) ? '&kategori='.$_GET['kategori'] : '' ?>
                            <?= !empty($_GET['search']) ? '&search='.$_GET['search'] : '' ?>"
                        class="page-btn <?= ($i == $page ? 'active' : '') ?>"
                    >
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        </section>
    </main>

    <div id="footer-placeholder"></div>
    <script src="assets/js/footer.js"></script>

</body>
</html>
