<?php
include("koneksi-bidang.php");

$result = pg_query($koneksi, "SELECT * FROM bidang_fokus ORDER BY bidangfokus_id ASC");
if (!$result) {
    die("Query gagal: " . pg_last_error($koneksi));
}
$rows = [];
while ($row = pg_fetch_assoc($result)) {
    $rows[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bidang Fokus</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/utils.css">
<link rel="stylesheet" href="assets/css/components.css">
<link rel="stylesheet" href="assets/css/responsive.css">
<link rel="stylesheet" href="assets/css/pages/tabelCRUD.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .navbar {
  background-color: #fff;
}
.navbar a,
.navbar span,
.navbar i,
.navbar .brand-title,
.navbar .brand-sub {
    color: #000 !important;
}
</style>
</head>
<body>

<div id="header-placeholder"></div>

<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">
        <div class="top-bar-page">
            <a href="admin_detail-bidang.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div class="title-container">
                <div class="title-row">
                    <h1 class="title-page">Bidang Fokus</h1>
                    <a href="bidangTambah.php" class="btn-add" data-add><i class="fa-solid fa-plus"></i></a>
                </div>

                <div class="search-row">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" class="search-input" placeholder="Search" data-search>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>User ID</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
<?php
$no = 1;
foreach ($rows as $row):
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['judul']) ?></td>
    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
    <td><img src="<?= htmlspecialchars($row['gambar']) ?>" alt="" style="width:70px;"></td>
    <td><?= htmlspecialchars($row['user_id']) ?></td>
    <td>
        <div class="action-buttons">
        <!-- Tombol Edit -->
        <a href="bidangEdit.php?id=<?= $row['bidangfokus_id'] ?>" class="btn-action btn-edit">
            <i class="fa-solid fa-pen"></i>
        </a>
        <!-- Tombol Delete -->
        <a href="bidangHapus.php?id=<?= $row['bidangfokus_id'] ?>" class="btn-action btn-delete"><i class="fa-solid fa-trash"></i></a>
    </div>
    </td>
</tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="btn-save-wrapper">
            <a href="admin_detail-bidang.php" class="btn-save" data-save>Simpan</a>
        </div>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>
</body>
</html>