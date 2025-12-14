<?php 
include "koneksi.php";

// --- SETTINGS ---
$limit = 2;                             
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : "semua";
$search   = isset($_GET['search']) ? $_GET['search'] : "";

// --- QUERY FILTER ---
$where = "WHERE 1=1";

if ($kategori != "semua") {
    $where .= " AND kategori = $1";
}

if (!empty($search)) {
    $search_param = "%" . $search . "%";

    if ($kategori != "semua") {
        $where .= " AND (judul ILIKE $2 OR deskripsi ILIKE $2)";
    } else {
        $where .= " AND (judul ILIKE $1 OR deskripsi ILIKE $1)";
    }
}

// --- QUERY UTAMA (Pagination) ---
if ($kategori != "semua") {
    $result = pg_query_params(
        $conn,
        "SELECT * FROM arsip $where ORDER BY arsip_id DESC LIMIT $limit OFFSET $start",
        !empty($search) ? [$kategori, $search_param] : [$kategori]
    );

    $countResult = pg_query_params(
        $conn,
        "SELECT COUNT(*) FROM arsip $where",
        !empty($search) ? [$kategori, $search_param] : [$kategori]
    );
} else {
    $result = pg_query_params(
        $conn,
        "SELECT * FROM arsip $where ORDER BY arsip_id DESC LIMIT $limit OFFSET $start",
        !empty($search) ? [$search_param] : []
    );

    $countResult = pg_query_params(
        $conn,
        "SELECT COUNT(*) FROM arsip $where",
        !empty($search) ? [$search_param] : []
    );
}

$totalData = pg_fetch_result($countResult, 0, 0);
$totalPages = ceil($totalData / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/arsipAdmin.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Kelola Arsip</title>
</head>

<body>

<div id="header-placeholder"></div>

<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="wrapper">
        <section class="section" id="arsip">

            <div class="arsip-title-header">
                <h1>Arsip</h1>
            </div>

            <!-- FILTER + SEARCH -->
            <div class="arsip-controls mb-5">
                <div class="d-flex gap-2">
                    <a href="arsipAdmin.php" class="btn-filter active">Semua</a>
                    <a href="penelitianAdmin.php?kategori=Penelitian" class="btn-filter">Penelitian</a>
                    <a href="pengabdianAdmin.php?kategori=Pengabdian" class="btn-filter">Pengabdian</a>
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
                            <a href="arsipDetailAdmin.php?id=<?= $row['arsip_id'] ?>" class="arsip-title mb-3">
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
            <div class="pagination">
                <?php for($i=1; $i <= $totalPages; $i++): ?>
                    <a 
                        href="?page=<?= $i ?>&kategori=<?= $kategori ?>&search=<?= $search ?>"
                        class="page-btn <?= $page == $i ? 'active' : '' ?>"
                    >
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

            <div class="kelola-wrapper">
                <a href="arsipTabel.php" class="btn-kelola">Kelola</a>
            </div>

        </section>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>
