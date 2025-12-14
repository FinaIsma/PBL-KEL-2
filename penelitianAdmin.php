<?php
include "koneksi.php";
$search = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$page = $_GET['page'] ?? 1;

// ==== CONFIG PAGINATION ====
$limit = 2; // jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ==== QUERY FILTER (Kategori wajib 'Penelitian') ====
$kategori = "Penelitian";
$where = ["kategori = 'Penelitian'"];

// Search
if (!empty($_GET['search'])) {
    $search = pg_escape_string($conn, $_GET['search']);
    $where[] = "(judul ILIKE '%$search%' OR deskripsi ILIKE '%$search%')";
}

$whereSQL = "WHERE " . implode(" AND ", $where);

// ==== Hitung total data ====
$countQuery = "SELECT COUNT(*) AS total FROM arsip $whereSQL";
$countResult = pg_query($conn, $countQuery);
$totalData = pg_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalData / $limit);

// ==== Ambil data ====
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

    <!-- CSS Base -->
    <link rel="stylesheet" href="assets/css/base.css">
    <!-- <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/layout.css">
    <link rel="stylesheet" href="assets/css/responsive.css"> -->
    <link rel="stylesheet" href="assets/css/pages/arsipAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/img/aura.png">

    <title>Kelola Arsip</title>
</head>

<body>

    <!-- Header -->
    <div id="header-placeholder"></div>
    <!-- Sidebar -->
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
                            value="<?= $_GET['search'] ?? '' ?>"
                        >
                    </form>
                </div>
            </div>

            <!-- LIST -->
            <div class="arsip-list">

                <?php if ($totalData == 0): ?>
                    <p>Tidak ada arsip kategori Penelitian ditemukan.</p>
                <?php endif; ?>

                <?php while ($row = pg_fetch_assoc($result)): ?>
                <!-- ITEM -->
                <div class="arsip-card" data-category="penelitian">
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
                                <div class="meta-item">Tanggal: <?= date("d M Y", strtotime($row['tanggal'])); ?></div>
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
   
    <script src="assets/js/headerSidebar.js"></script>
    <script src="assets/js/downloadArsip.js"></script>
    <script type="module" src="assets/js/main.js"></script>

</body>
</html>