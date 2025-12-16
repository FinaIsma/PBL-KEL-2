<?php
session_start();
require_once "backend/config.php";

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$success = "";
$error   = "";
$mediaName = null;
$mediaDB   = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul   = trim($_POST['judul']);
    $user_id = $_SESSION['user_id'];

    if (!empty($_FILES['media_path']['name'])) {

        if ($_FILES['media_path']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['media_path']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];

            if (!in_array($ext, $allowed)) {
                $error = "Format file tidak diizinkan.";
            } else {

                $uploadDir = __DIR__ . "/upload/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $mediaName  = "dokumentasi_" . time() . "." . $ext;
                $targetPath = $uploadDir . $mediaName;

                if (move_uploaded_file($_FILES['media_path']['tmp_name'], $targetPath)) {
                    $mediaDB = "upload/" . $mediaName;
                } else {
                    $error = "Gagal mengupload gambar!";
                }
            }
        } else {
            $error = "Upload error code: " . $_FILES['media_path']['error'];
        }
    }

    if ($error === "") {
        try {
            $stmt = $db->prepare("
                INSERT INTO dokumentasi (judul, media_path, user_id)
                VALUES (:judul, :media_path, :user_id)
            ");

            $stmt->execute([
                'judul'      => $judul,
                'media_path' => $mediaDB,
                'user_id'    => $user_id
            ]);

            header("Location: tabelDokumentasi.php");
            exit;

        } catch (PDOException $e) {
            $error = "Gagal menambahkan data ke database: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Dokumentasi</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

/* ====== LAYOUT (DISESUAIKAN DARI KODE 2) ====== */
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

/* ====== FORM ELEMENTS ====== */
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

.form-add input:focus,
.form-add textarea:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 4px rgba(255, 184, 77, 0.6);
}

/* ====== BUTTONS ====== */
.btn-submit {
    background: var(--secondary);
    color: #000;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(255, 184, 77, 0.3);
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
    transition: 0.2s;
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
        <h1>Tambah Dokumentasi</h1>
    </section>

    <!-- Alerts -->
    <?php if (!empty($success)): ?>
        <div style="padding: 10px 80px; color: green; font-weight: bold;"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">

        <form method="POST" enctype="multipart/form-data" class="form-add">

            <label>Judul</label>
            <input type="text" name="judul" required>

            <label>File / Gambar</label>
            <input type="file" name="media_path" accept="image/png, image/jpeg" required>

            <button class="btn-submit" type="submit">Simpan</button>
            <a href="tabelDokumentasi.php" class="btn-cancel">Batal</a>

        </form>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
