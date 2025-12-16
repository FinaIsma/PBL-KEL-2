<?php
session_start();
include("db.php");

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    die("ID pengelola tidak ditemukan");
}

$result = pg_query($conn, "SELECT * FROM pengelola_lab WHERE pengelola_id = $id");
if (!$row = pg_fetch_assoc($result)) {
    die("Data tidak ditemukan");
}

$success = "";
$error = "";
$fotoName = $row['foto'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama    = trim($_POST['nama']);
    $jabatan = trim($_POST['jabatan']);
    $kontak  = trim($_POST['kontak']);
    $editor  = $_SESSION['user_id'];

    if (!empty($_FILES['foto']['name'])) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];

        if (!in_array($ext, $allowed)) {
            $error = "Format file tidak diizinkan.";
        } else {
            $uploadDir = __DIR__ . '/assets/img/pengelola/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $newFotoName = "pengelola_" . time() . "." . $ext;
            $targetPath = $uploadDir . $newFotoName;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {

                if (!empty($row['foto']) && file_exists(__DIR__ . "/" . $row['foto'])) {
                    unlink(__DIR__ . "/" . $row['foto']);
                }

                $fotoName = "assets/img/pengelola/" . $newFotoName;

            } else {
                $error = "Gagal mengupload foto!";
            }
        }
    }

    if ($error === "") {

        $sql = "UPDATE pengelola_lab 
                SET nama='$nama',
                    jabatan='$jabatan',
                    kontak='$kontak',
                    foto='$fotoName',
                    user_id=$editor
                WHERE pengelola_id=$id";

        if (pg_query($conn, $sql)) {
            header("Location: tabelPengelola.php");
            exit;
        } else {
            $error = "Gagal memperbarui data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengelola Lab</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="assets/css/CRUDTable.css"> <!-- WAJIB -->

<style>

/* ====== LAYOUT ====== */
main, .content {
    margin-top: 100px;
}

.sidebar {
    width: 220px;
    position: fixed;
    top: 83.5px;
    left: 0;
    height: calc(100vh - 83.5px);
}

.navbar {
    box-shadow: 3px 5px 10px rgba(0, 0, 0, 0.15) !important;
    background-color: #fff;
}

.content {
    margin-left: 220px;
    padding-top: 100px;
    transform: scale(0.8);
    transform-origin: top left;
    width: calc((100% - 220px) / 0.8);
    margin-top: -110px !important;
}

.hero-section-admin {
    padding-left: 80px;
}

/* ====== FORM SECTION ====== */
.form-section {
    padding: 20px 60px;
}

.form-wrapper {
    background: #fff;
    border-radius: 12px;
    padding: 30px 40px;
    box-shadow: 0 5px 20px rgba(10, 6, 1, 0.15);
    border: 1px solid #ddd;
}

/* ====== FORM ELEMENTS (Samakan dengan kode 2) ====== */
.form-add label {
    font-family: var(--font-body);
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #000;
}

.form-add input,
.form-add textarea,
.form-add select {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #999;
    font-size: 13px;
    font-family: var(--font-body);
    color: #000;
    background: #f9f9f9;
    outline: none;
    margin-bottom: 22px;
}

.form-add input:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 4px rgba(255,184,77,0.6);
}

/* FOTO */
.preview-foto img {
    width: 120px;
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    margin-bottom: 12px;
}

/* BUTTONS */
.btn-submit {
    background: var(--secondary);
    color: #000;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(255,184,77,0.3);
    transition: 0.3s ease;
}

.btn-submit:hover {
    background: #FF9A3D;
    transform: translateY(-2px);
}

.btn-cancel {
    margin-left: 12px;
    padding: 12px 30px;
    background: #e0e0e0;
    color: #000;
    border-radius: 8px;
    font-size: 16px;
    text-decoration: none;
    font-family: var(--font-body);
}

.btn-cancel:hover {
    background: #ccc;
}
</style>

</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Edit Pengelola Lab</h1>
    </section>

    <?php if ($error): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">

        <div class="form-wrapper">
        <form method="POST" enctype="multipart/form-data" class="form-add">

            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>

            <label>Jabatan</label>
            <input type="text" name="jabatan" value="<?= htmlspecialchars($row['jabatan']) ?>" required>

            <label>Kontak</label>
            <input type="text" name="kontak" value="<?= htmlspecialchars($row['kontak']) ?>" required>

            <label>Foto</label>

            <?php if (!empty($row['foto'])): ?>
                <div class="preview-foto">
                    <img src="<?= $row['foto'] ?>" alt="Foto lama">
                </div>
            <?php endif; ?>

            <input type="file" name="foto">

            <button class="btn-submit" type="submit">Simpan</button>
            <a href="tabelPengelola.php" class="btn-cancel">Batal</a>

        </form>
        </div>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
