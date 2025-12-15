<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
include("db.php"); 

$showConfirm = false;
$confirmData = null;

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $res = pg_query($conn, "SELECT nama FROM pengelola_lab WHERE pengelola_id = $delete_id");
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
    <title>Pengelola Lab</title>

    <!-- FIXED PATH -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="assets/css/pages/tabelCRUD.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <div id="header"></div>
            <div id="sidebar"></div>

        <main class="content">

            <div class="top-bar-page">
                <a href="profil-admin.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <div class="title-container">
                    <div class="title-row">
                        <h1 class="title-page">Pengelola Lab</h1>

                        <a href="addPengelola.php" class="btn-add" data-add>
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
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Kontak</th>
                            <th>Foto</th>
                            <th>User ID</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="table-body">
    <?php
$result = pg_query($conn, "SELECT * FROM pengelola_lab");
$no = 1; // counter mulai dari 1
while ($row = pg_fetch_assoc($result)):
?>
<tr>
    <td><?= $no++ ?></td> <!-- ini nomor urut -->
    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['jabatan']) ?></td>
    <td><?= htmlspecialchars($row['kontak']) ?></td>
    <td><img src="<?= htmlspecialchars($row['foto']) ?>" alt="" style="width:70px;"></td>
    <td><?= htmlspecialchars($row['user_id']) ?></td>
    <td>
        <div class="action-buttons">
            <a href="editPengelola.php?id=<?= $row['pengelola_id'] ?>" class="btn-action btn-edit">
                <i class="fa-solid fa-pen"></i>
            </a>
            <a href="tabelPengelola.php?delete_id=<?= $row['pengelola_id'] ?>" class="btn-action btn-delete">
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
                <a href="profil-admin.php" class="btn-save" data-save>Simpan</a>
            </div>

        </main>


    <script src="assets/js/sidebarHeader.js"></script>
    <script type="module" src="assets/js/main.js"></script>
</body>
<?php if ($showConfirm && $confirmData): ?>
<!-- Tambahkan style langsung di halaman -->
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

/* Tombol */
.modal-form {
    display: flex;
    justify-content: center;
    gap: 10px; /* jarak tombol */
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

/* Tombol hapus */
.btn-delete {
    background-color: #e74c3c;
    color: #fff;
}

.btn-delete:hover {
    background-color: #c0392b;
}

/* Tombol batal */
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
        <form method="POST" action="deletePengelola.php" class="modal-form">
            <input type="hidden" name="id" value="<?= $confirmData['id'] ?>">
            <button type="submit" class="btn btn-delete">Ya, hapus</button>
            <a href="tabelPengelola.php" class="btn btn-cancel">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>



</html>
