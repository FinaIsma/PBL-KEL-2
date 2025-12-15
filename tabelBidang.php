<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once "backend/config.php";

$showConfirm = false;
$confirmData = null;

/* ===== AMBIL DATA UNTUK MODAL HAPUS ===== */
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    $stmt = $db->prepare("SELECT judul FROM bidang_fokus WHERE bidangfokus_id = ?");
    $stmt->execute([$delete_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $showConfirm = true;
        $confirmData = $row;
        $confirmData['id'] = $delete_id;
    }
}

/* ===== AMBIL SEMUA DATA ===== */
$stmt = $db->prepare("SELECT * FROM bidang_fokus ORDER BY bidangfokus_id ASC");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bidang Fokus</title>

<link rel="stylesheet" href="assets/css/base.css">
<!-- <link rel="stylesheet" href="assets/css/utils.css"> -->
<!-- <link rel="stylesheet" href="assets/css/components.css"> -->
<link rel="stylesheet" href="assets/css/responsive.css">
<link rel="stylesheet" href="assets/css/pages/tabelCRUD.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
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

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <div class="top-bar-page">
        <a href="admin_bidang-fokus.php" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="title-container">
            <div class="title-row">
                <h1 class="title-page">Bidang Fokus</h1>
                <a href="bidangTambah.php" class="btn-add">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>

            <div class="search-row">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search">
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
            <tbody>

            <?php $no = 1; foreach ($rows as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="td-image">
                        <?php if (!empty($row['gambar'])): ?>
                            <div class="img-box">
                                <img src="<?= htmlspecialchars($row['gambar']) ?>" alt="">
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="bidangEdit.php?id=<?= $row['bidangfokus_id'] ?>" class="btn-action btn-edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="tabelBidang.php?delete_id=<?= $row['bidangfokus_id'] ?>" class="btn-action btn-delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <div class="btn-save-wrapper">
        <a href="admin_detail-bidang.php" class="btn-save">Simpan</a>
    </div>

</main>

<script src="assets/js/sidebarHeader.js"></script>

<?php if ($showConfirm && $confirmData): ?>
<style>
.modal-overlay {
    position: fixed;
    inset: 0;
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
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: bold;
    font-size: 14px;
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

/* FIX FINAL THUMBNAIL TABLE */
.table td .img-box {
    width: 15px !important;
    height: 15px !important;
    margin: auto;
}

.table td .img-box img {
    width: 15px !important;
    height: 15px !important;
    max-width: none !important;
    max-height: none !important;
    object-fit: cover;
    display: block;
}

.table img {
    width: auto !important;
    height: auto !important;
}

</style>

<div class="modal-overlay">
    <div class="modal-box">
        <p>
            Yakin ingin menghapus
            <strong><?= htmlspecialchars($confirmData['judul']) ?></strong>?
        </p>
        <form method="POST" action="bidangHapus.php" class="modal-form">
            <input type="hidden" name="id" value="<?= $confirmData['id'] ?>">
            <button type="submit" class="btn btn-delete">Ya, hapus</button>
            <a href="tabelBidang.php" class="btn btn-cancel">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>

</body>
</html>
