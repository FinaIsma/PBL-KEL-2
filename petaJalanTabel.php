<?php
include("koneksi.php");

function getNextId($koneksi) {
    $query = "SELECT MIN(t1.peta_id + 1) AS next_id
              FROM peta_jalan t1
              LEFT JOIN peta_jalan t2 ON t1.peta_id + 1 = t2.peta_id
              WHERE t2.peta_id IS NULL";
    $res = pg_query($koneksi, $query);
    $row = pg_fetch_assoc($res);
    if ($row && $row['next_id']) {
        return $row['next_id'];
    }
    return 1;
}

$query = "SELECT * FROM peta_jalan ORDER BY tahun ASC, peta_id ASC";
$result = pg_query($koneksi, $query);

$petaJalan = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $petaJalan[] = $row;
    }
} else {
    echo "Query gagal: " . pg_last_error($koneksi);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peta Jalan Admin</title>
<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebar.css">
<link rel="stylesheet" href="assets/css/pages/petaJalanTable.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div id="header-placeholder"></div>
<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">
        <section class="page-header">
            <div class="header-top">
                <div class="header-left">
                    <button class="back-button" onclick="window.location.href='petaJalanAdmin.php'">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <div class="title-row">
                        <h1>Peta Jalan</h1>
                        <a href="petaJalanTambah.php" class="add-button">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search">
                </div>
            </div>
        </section>

        <section class="table-section">
            <form method="POST" action="petaJalanSimpan.php">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Tahun</th>
                                <th>Deskripsi</th>
                                <th>File</th>
                                <th>Editor ID</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($petaJalan as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['peta_id']) ?></td>
                                <td><?= htmlspecialchars($item['judul']) ?></td>
                                <td><?= htmlspecialchars($item['tahun']) ?></td>
                                <td><?= htmlspecialchars($item['deskripsi']) ?></td>
                                <td>
                                    <div class="file-preview">
                                        <?php 
                                        $file_path = "uploads/" . basename($item['file_path']); 
                                        if(!empty($item['file_path']) && file_exists($file_path)): ?>
                                            <a href="<?= $file_path ?>" target="_blank">
                                                <img src="assets/img/pdf_icon.png" alt="PDF" class="pdf-icon">
                                            </a>
                                        <?php else: ?>
                                            <span>Tidak ada</span>
                                        <?php endif; ?>

                                    </div>
                                </td>
                                <td><?= htmlspecialchars($item['user_id']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="petaJalanEdit.php?peta_id=<?= $item['peta_id'] ?>" class="btn-edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="petaJalanHapus.php?peta_id=<?= $item['peta_id'] ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="save-button-wrapper">
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </section>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

<script>
const searchInput = document.querySelector(".search-box input");
const rows = document.querySelectorAll("table.data-table tbody tr");

searchInput.addEventListener("keyup", function () {
    let keyword = this.value.toLowerCase();

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? "" : "none";
    });
});
</script>

</body>
</html>
