<?php
session_start();
require_once "backend/config.php";

// CEK LOGIN
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login.");
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {

    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $kategori  = trim($_POST['kategori']);
    $penulis   = trim($_POST['penulis']);
    $tanggal   = $_POST['tanggal'];

    /* ===== VALIDASI FILE PDF ===== */
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("File PDF wajib diupload.");
    }

    $fileName = $_FILES['file']['name'];
    $fileTmp  = $_FILES['file']['tmp_name'];
    $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== 'pdf') {
        die("File harus berformat PDF.");
    }

    /* ===== UPLOAD FILE ===== */
    $uploadDir = "upload/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $newFileName = time() . "_" . $fileName;
    $uploadPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmp, $uploadPath)) {
        die("Gagal mengupload file PDF.");
    }

    /* ===== INSERT DATABASE (PDO) ===== */
    try {
        $stmt = $db->prepare("
            INSERT INTO arsip 
            (judul, deskripsi, kategori, penulis, tanggal, file_path, user_id)
            VALUES 
            (:judul, :deskripsi, :kategori, :penulis, :tanggal, :file_path, :user_id)
        ");

        $stmt->execute([
            'judul'     => $judul,
            'deskripsi' => $deskripsi,
            'kategori'  => $kategori,
            'penulis'   => $penulis,
            'tanggal'   => $tanggal,
            'file_path' => $newFileName,
            'user_id'   => $user_id
        ]);

        header("Location: arsipAdmin.php");
        exit;

    } catch (PDOException $e) {
        die("Gagal insert data: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Arsip</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

main, .content {
    margin-top: 100px; /* tinggi navbar */
}

/* POSISI SIDEBAR */
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

.menu a { 
    display: block; 
    padding: 12px; 
    color: #fff; 
    opacity: .85; 
    margin-bottom: 6px; 
    border-radius: 6px; 
}

.menu a.active, .menu a:hover { 
    background: rgba(255,255,255,.15); 
    opacity: 1; 
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

/* === FORM SECTION === */
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

/* === FORM INPUT === */
.form-add label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-add input,
.form-add select,
.form-add textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #999;
    background: #f9f9f9;
    margin-bottom: 22px;
    font-size: 13px;
}

.form-add textarea {
    resize: vertical;
}

/* === BUTTONS === */
.btn-submit {
    background: var(--secondary);
    color: #000;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
}

.btn-cancel {
    margin-left: 12px;
    padding: 12px 30px;
    background: #e0e0e0;
    border-radius: 8px;
    text-decoration: none;
    color: #000;
}

</style>

</head>
<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Tambah Arsip</h1>
    </section>

    <section class="form-section">

        <div class="form-wrapper">

            <form action="" method="POST" enctype="multipart/form-data" class="form-add">

                <label>Judul</label>
                <input type="text" name="judul" required>

                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Penelitian">Penelitian</option>
                    <option value="Pengabdian">Pengabdian</option>
                </select>

                <label>Penulis</label>
                <input type="text" name="penulis" required>

                <label>Tanggal</label>
                <input type="date" name="tanggal" required>

                <label>File PDF</label>
                <input type="file" name="file" accept="application/pdf" required>

                <label>Deskripsi</label>
                <textarea name="deskripsi" required></textarea>

                <input type="hidden" name="user_id" value="1">

                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="arsipTabel.php" class="btn-cancel">Batal</a>

            </form>

        </div>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
