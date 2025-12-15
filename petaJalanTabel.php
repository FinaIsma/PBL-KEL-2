<?php
require_once "backend/config.php";

$showConfirm = false;
$confirmData = null;

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $sql = "SELECT judul FROM peta_jalan WHERE peta_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $delete_id]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $showConfirm = true;
        $confirmData = $row;
        $confirmData['id'] = $delete_id;
    }
}

// Ambil data
try {
    $stmt = $db->query("SELECT * FROM peta_jalan ORDER BY peta_id ASC");
    $petaJalan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Peta Jalan</title>

<!-- STYLE SAMA DENGAN HALAMAN TABEL LAIN -->
<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/utils.css">
<link rel="stylesheet" href="assets/css/components.css">
<link rel="stylesheet" href="assets/css/responsive.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="assets/css/pages/tabelCRUD.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <!-- 🔥 SAMA PERSIS STRUKTURNYA DENGAN HALAMAN PROFIL / BIDANG FOKUS -->
    <div class="top-bar-page">
        <a href="petaJalanAdmin.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="title-container">
            <div class="title-row">
                <h1 class="title-page">Peta Jalan</h1>
                <a href="petaJalanTambah.php" class="btn-add">
                    <i class="fa-solid fa-plus"></i>
                </a>
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
                    <th>Tahun</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>User ID</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
            <?php $no=1; foreach($petaJalan as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['tahun']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td>
                        <div class="file-preview">
                            <?php 
                            $file = "uploads/" . basename($row['file_path']);
                            if (!empty($row['file_path']) && file_exists($file)): ?>
                                <a href="<?= $file ?>" target="_blank">
                                    <img src="assets/img/PDF.svg" style="width:50px;height:50px;vertical-align:middle;">
                                </a>
                            <?php else: ?>
                                <span>Tidak ada</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>

                    <td>
                        <div class="action-buttons">
                            <a href="petaJalanEdit.php?peta_id=<?= $row['peta_id'] ?>" class="btn-action btn-edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="petaJalanTabel.php?delete_id=<?= $row['peta_id'] ?>" 
                            class="btn-action btn-delete"><i class="fa-solid fa-trash"></i>
                        </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="btn-save-wrapper">
        <a href="petaJalanAdmin.php" class="btn-save">Simpan</a>
    </div>

</main>

<script src="assets/js/sidebarHeader.js"></script>

<script>
document.querySelector("[data-search]").addEventListener("keyup", function(){
    let keyword = this.value.toLowerCase();
    document.querySelectorAll("#table-body tr").forEach(row=>{
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? "" : "none";
    });
});
</script>

</body>
<?php if ($showConfirm && $confirmData): ?>
<style>
.modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-box {
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    text-align: center;
    min-width: 320px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.modal-box p {
    font-size: 16px;
    margin-bottom: 25px;
}

.modal-form {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.btn-delete {
    background: #e74c3c;
    color: #fff;
}

.btn-delete:hover {
    background: #c0392b;
}

.btn-cancel {
    background: #95a5a6;
    color: #fff;
}

.btn-cancel:hover {
    background: #7f8c8d;
}
</style>

<div class="modal-overlay">
    <div class="modal-box">
        <p>
            Yakin ingin menghapus
            <strong><?= htmlspecialchars($confirmData['judul']) ?></strong>?
        </p>

        <form method="POST" action="petaJalanHapus.php" class="modal-form">
            <input type="hidden" name="id" value="<?= $confirmData['id'] ?>">
            <button type="submit" class="btn btn-delete">Ya, hapus</button>
            <a href="petaJalanTabel.php" class="btn btn-cancel">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>

</html>
