<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("backend/config.php"); 

$showConfirm = false;
$confirmData = null;

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $stmt = $db->prepare("SELECT judul FROM agenda WHERE agenda_id = ?");
    $stmt->execute([$delete_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $showConfirm = true;
        $confirmData = $row;
        $confirmData['id'] = $delete_id;
    }
}

// Ambil keyword search
$search = isset($_GET['search']) ? $_GET['search'] : "";

if ($search != "") {
    $stmt = $db->prepare("
        SELECT * FROM agenda
        WHERE judul ILIKE :search
           OR deskripsi ILIKE :search
           OR hari_tgl::text ILIKE :search
           OR user_id::text ILIKE :search
        ORDER BY agenda_id ASC
    ");
    $stmt->execute([
        ':search' => '%' . $search . '%'
    ]);
} else {
    $stmt = $db->prepare("SELECT * FROM agenda ORDER BY agenda_id ASC");
    $stmt->execute();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Agenda</title>

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
        <a href="javascript:void(0)" class="btn-back" onclick="history.back()">
                <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="title-container">
            <div class="title-row">
                <h1 class="title-page">Agenda</h1>
                <a href="createAgenda.php" class="btn-add">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>

            <div class="search-row">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input 
                        type="text"
                        class="search-input"
                        placeholder="Search"
                        value="<?= htmlspecialchars($search) ?>"
                        oninput="window.location='?search=' + this.value"
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari/Tgl</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>User ID</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php 
            $no = 1;
            foreach ($result as $row):
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['hari_tgl']) ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="editAgenda.php?id=<?= $row['agenda_id'] ?>" class="btn-action btn-edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="tabelAgenda.php?delete_id=<?= $row['agenda_id'] ?>" 
                               class="btn-action btn-delete">
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
        <a href="galeriAdmin.php" class="btn-save">Simpan</a>
    </div>

</main>

<script src="assets/js/sidebarHeader.js"></script>
<script type="module" src="assets/js/main.js"></script>

<?php if ($showConfirm && $confirmData): ?>
<style>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.modal-box {
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    text-align: center;
    min-width: 320px;
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
}
.btn-delete {
    background: #e74c3c;
    color: #fff;
}
.btn-cancel {
    background: #95a5a6;
    color: #fff;
    text-decoration: none;
    line-height: 38px;
}
</style>

<div class="modal-overlay">
    <div class="modal-box">
        <p>
            Yakin ingin menghapus agenda<br>
            <strong><?= htmlspecialchars($confirmData['judul']) ?></strong>?
        </p>

        <form method="POST" action="deleteAgenda.php" class="modal-form">
            <input type="hidden" name="id" value="<?= $confirmData['id'] ?>">
            <button type="submit" class="btn btn-delete">Ya, Hapus</button>
            <a href="tabelAgenda.php" class="btn btn-cancel">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>

</body>
</html>
