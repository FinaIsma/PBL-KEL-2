<?php
include "koneksi.php";

// Ambil keyword jika ada
$search = isset($_GET['search']) ? $_GET['search'] : "";

if ($search != "") {
    // Jika user mencari data
    $query = "
        SELECT * FROM agenda
        WHERE judul ILIKE $1
           OR deskripsi ILIKE $1
           OR hari_tgl::text ILIKE $1
           OR user_id::text ILIKE $1
        ORDER BY agenda_id ASC
    ";
    $result = pg_query_params($conn, $query, ['%' . $search . '%']);
} else {
    // Jika tidak ada keyword → tampilkan semua
    $query = "SELECT * FROM agenda ORDER BY agenda_id ASC";
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
    <title>Kelola Agenda</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/arsipTabel.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
</head>
<body>

<!-- Header -->
<div id="header-placeholder"></div>

<div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <!-- Main Content -->
    <main class="arsip-main">
        <div class="arsip-header">
            <div class="header-left">
                <a href="galeriAdmin.php" class="btn-back"></a>
                <h1 class="arsip-title">Agenda</h1>
                <a href="createAgenda.php" class="btn-add"></a>
            </div>
            <div class="search-wrapper">
                <form action="" method="GET">
                    <input 
                        type="text" 
                        class="search-input" 
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
                        <th>Hari/Tgl</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
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
                            <td><?= $no ?></td>
                            <td><?= $row['hari_tgl'] ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td><?= $row['user_id'] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="editAgenda.php?id=<?= $row['agenda_id'] ?>" 
                                    class="btn-action btn-edit" 
                                    title="Edit"></a>

                                    <a href="deleteAgenda.php?id=<?= $row['agenda_id'] ?>" 
                                    class="btn-action btn-delete" 
                                    title="Delete"
                                    onclick="return confirm('Hapus data ini?')"></a>
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
