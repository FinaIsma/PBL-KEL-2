<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error   = "";
$mediaDB = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $editor    = $_SESSION['user_id'];

    if (!empty($_FILES['media']['name'])) {

        if ($_FILES['media']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowed)) {
                $error = "Format gambar tidak diizinkan. Gunakan JPG, PNG, atau WEBP.";
            } else {

                $uploadDir = __DIR__ . "/assets/img/media/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $mediaName  = "sarpras_" . time() . "_" . rand(100,999) . "." . $ext;
                $targetPath = $uploadDir . $mediaName;

                if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {
                    $mediaDB = "assets/img/media/" . $mediaName;
                } else {
                    $error = "Gagal mengupload gambar!";
                }
            }

        } else {
            $error = "Terjadi kesalahan saat upload gambar.";
        }
    } else {
        $error = "Gambar wajib diupload!";
    }

    if ($error === "") {

        $sql = "
            INSERT INTO sarana_prasarana (judul, deskripsi, media_path, user_id)
            VALUES ($1, $2, $3, $4)
        ";

        $result = pg_query_params($conn, $sql, [
            $judul,
            $deskripsi,
            $mediaDB,
            $editor
        ]);

        if ($result) {
            header("Location: tabelSarpras.php");
            exit;
        } else {
            $error = "Gagal menambahkan data ke database!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Sarana & Prasarana</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
main, .content { margin-top: 100px; }

.sidebar {
    width: 220px;
    position: fixed;
    top: 83.5px;
    left: 0;
    height: calc(100vh - 83.5px);
}

.navbar {
    box-shadow: 3px 5px 10px rgba(0,0,0,.15);
    background: #fff;
}

.content {
    margin-left: 220px;
    padding-top: 100px;
    transform: scale(.8);
    transform-origin: top left;
    width: calc((100% - 220px) / .8);
    margin-top: -110px;
}

.hero-section-admin { padding-left: 80px; }

.form-section { padding: 20px 60px; }

.form-wrapper {
    background: #fff;
    border-radius: 12px;
    padding: 30px 40px;
    box-shadow: 0 5px 20px rgba(10,6,1,.15);
    border: 1px solid #ddd;
}

.form-add label {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-add input,
.form-add textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #999;
    margin-bottom: 22px;
}

.btn-submit {
    background: var(--secondary);
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
}

.alert-error {
    padding: 14px 20px;
    background: #ffdddd;
    border-left: 5px solid #e74c3c;
    border-radius: 6px;
    margin-bottom: 20px;
}
</style>
</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Tambah Sarana & Prasarana</h1>
    </section>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <section class="form-section">
        <div class="form-wrapper">

            <form method="POST" enctype="multipart/form-data" class="form-add">

                <label>Judul</label>
                <input type="text" name="judul" required>

                <label>Deskripsi</label>
                <textarea name="deskripsi"></textarea>

                <label>Gambar</label>
                <input type="file" name="media" accept=".jpg,.jpeg,.png,.webp" required>

                <button type="submit" class="btn-submit">Simpan</button>
                <a href="tabelSarpras.php" class="btn-cancel">Batal</a>

            </form>

        </div>
    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>
</body>
</html>
