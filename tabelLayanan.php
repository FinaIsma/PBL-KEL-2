<?php 
include("db.php"); 

$showConfirm = false;
$confirmData = null;

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $res = pg_query($conn, "SELECT nama FROM layanan WHERE layanan_id = $delete_id");
    if ($row = pg_fetch_assoc($res)) {
        $showConfirm = true;
        $confirmData = $row;
        $confirmData['id'] = $delete_id;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Layanan</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/utils.css">
<link rel="stylesheet" href="assets/css/components.css">
<link rel="stylesheet" href="assets/css/responsive.css">
<link rel="stylesheet" href="assets/css/pages/tabelCRUD.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div id="header-placeholder"></div>

<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">
        <div class="top-bar-page">
            <a href="layanan-admin.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div class="title-container">
                <div class="title-row">
                    <h1 class="title-page">Layanan</h1>
                    <a href="addLayanan.php" class="btn-add" data-add>
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
                        <th>Jenis</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>User ID</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
<?php
$result = pg_query($conn, "SELECT * FROM layanan ORDER BY layanan_id ASC");
$no = 1;
while ($row = pg_fetch_assoc($result)):
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['jenis']) ?></td>
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
    <td><?= htmlspecialchars($row['user_id']) ?></td>
    <td>
        <div class="action-buttons">
            <a href="editLayanan.php?id=<?= $row['layanan_id'] ?>" class="btn-action btn-edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <a href="tabelLayanan.php?delete_id=<?= $row['layanan_id'] ?>" class="btn-action btn-delete">
                <i class="fa-solid fa-trash"></i>
            </a>
        </div>
    </td>
</tr>
<?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="btn-save-wrapper">
            <a href="layanan-admin.php" class="btn-save" data-save>Simpan</a>
        </div>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>
<script type="module" src="assets/js/main.js"></script>
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
    max-width: 90%;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.modal-box p {
    font-size: 16px;
    margin-bottom: 25px;
}

.modal-form {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: bold;
    font-size: 14px;
    line-height: 1.4;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.2s;
    text-decoration: none;
    border: none;
}

.btn-delete {
    background-color: #e74c3c;
    color: #fff;
}

.btn-delete:hover {
    background-color: #c0392b;
}

.btn-cancel {
    background-color: #95a5a6;
    color: #fff;
}

.btn-cancel:hover {
    background-color: #7f8c8d;
}
</style>

<div class="modal-overlay">
    <div class="modal-box">
        <p>Yakin ingin menghapus <strong><?= htmlspecialchars($confirmData['nama']) ?></strong>?</p>
        <form method="POST" action="deleteLayanan.php" class="modal-form">
            <input type="hidden" name="id" value="<?= $confirmData['id'] ?>">
            <button type="submit" class="btn btn-delete">Ya, hapus</button>
            <a href="tabelLayanan.php" class="btn btn-cancel">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>

</html>
