<?php
include "koneksi.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";

if ($search != "") {
    // Jika user mencari data
    $query = "
        SELECT * FROM arsip
        WHERE judul ILIKE $1
           OR kategori ILIKE $1
           OR deskripsi ILIKE $1
           OR penulis ILIKE $1
           OR tanggal::text ILIKE $1
           OR file_path ILIKE $1
           OR thumbnail ILIKE $1
           OR user_id::text ILIKE $1
        ORDER BY arsip_id ASC
    ";

    $result = pg_query_params($conn, $query, ['%' . $search . '%']);
} else {
    $query = "SELECT * FROM arsip ORDER BY arsip_id ASC";
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
    <title>Kelola Arsip</title>

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
                <a href="arsipAdmin.php" class="btn-back"></a>
                <h1 class="arsip-title">Arsip</h1>
                <a href="createArsip.php" class="btn-add"></a>
            </div>
           <div class="search-wrapper">
               <form method="GET" action="">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search"
                        value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>"
                    >
                </form>
            </div>
        </div>

        <div class="arsip-table-wrapper">
            <table class="arsip-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>File</th>
                        <th>Thumbnail</th>
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
                            <td><?= $no; ?></td>   <!-- NOMOR URUT -->
                            <td><?= $row['kategori']; ?></td>
                            <td><?= $row['judul']; ?></td>
                            <td><?= $row['deskripsi']; ?></td>
                            <td><?= $row['penulis']; ?></td>
                            <td><?= date("d M Y", strtotime($row['tanggal'])); ?></td>

                            <td>
                                <div class="file-preview"><?= $row['file_path']; ?></div>
                            </td>

                            <td>
                                <?php if ($row['thumbnail']) : ?>
                                    <img src="upload/<?= $row['thumbnail']; ?>" class="thumb-preview">
                                <?php else : ?>
                                    <span>Tidak ada</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $row['user_id']; ?></td>

                            <td>
                                <div class="action-buttons">
                                    <a href="editArsip.php?id=<?= $row['arsip_id']; ?>" class="btn-action btn-edit"></a>
                                    <a onclick="return confirm('Hapus data ini?')" 
                                    href="deleteArsip.php?arsip_id=<?= $row['arsip_id']; ?>" 
                                    class="btn-action btn-delete">
                                    </a>
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
            <a href="arsipAdmin.php" class="btn-simpan">Simpan</a>
        </div>


    </main>

    <script src="assets/js/headerSidebar.js"></script>
</body>
</html>
