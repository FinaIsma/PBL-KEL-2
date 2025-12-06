<?php
include "koneksi.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";

if ($search != "") {
    $query = "
        SELECT * FROM dokumentasi
        WHERE judul ILIKE $1 
        OR media_path ILIKE $1
        OR user_id::text ILIKE $1
        ORDER BY dokumentasi_id ASC
    ";

    $result = pg_query_params($conn, $query, ['%' . $search . '%']);
} else {
    $query = "SELECT * FROM dokumentasi ORDER BY dokumentasi_id ASC";
    $result = pg_query($conn, $query);
}

if (!$result) {
    die("Query failed: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokumentasi</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/arsipTabel.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">

    <style>
        .thumb-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div id="header-placeholder"></div>

<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="arsip-main">

        <div class="arsip-header">
            <div class="header-left">
                <a href="galeriAdmin.php" class="btn-back"></a>
                <h1 class="arsip-title">Dokumentasi</h1>
                <a href="createDokumentasi.php" class="btn-add"></a>
            </div>

            <div class="search-wrapper">
                <form method="GET" action="">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search"
                        value="<?= htmlspecialchars($search); ?>"
                    >
                </form>
            </div>
        </div>

        <div class="arsip-table-wrapper">
            <table class="arsip-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Editor ID</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                $no = 1; 
                while ($row = pg_fetch_assoc($result)) : 
                ?>
                    <tr>
                        <td><?= $no; ?></td>

                        <td>
                            <?php if ($row['media_path']) : ?>
                                <img src="upload/<?= $row['media_path']; ?>" class="thumb-preview">
                            <?php else : ?>
                                <span>Tidak ada</span>
                            <?php endif; ?>
                        </td>

                        <td><?= $row['judul']; ?></td>
                        <td><?= $row['user_id']; ?></td>

                        <td>
                            <div class="action-buttons">
                                <a href="editDokumentasi.php?id=<?= $row['dokumentasi_id']; ?>" class="btn-action btn-edit"></a>

                                <a onclick="return confirm('Hapus data ini?')"
                                   href="deleteDokumentasi.php?dokumentasi_id=<?= $row['dokumentasi_id']; ?>"
                                   class="btn-action btn-delete"></a>
                            </div>
                        </td>
                    </tr>

                <?php 
                $no++; 
                endwhile; 
                ?>
                </tbody>
            </table>
        </div>

        <div class="button-footer">
            <a href="galeriAdmin.php" class="btn-simpan">Simpan</a>
        </div>

    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>
