<?php
include "koneksi.php";

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $kategori  = $_POST['kategori'];
    $penulis   = $_POST['penulis'];
    $tanggal   = $_POST['tanggal'];
    $user_id   = $_POST['user_id'];

    // Upload Thumbnail
    $thumbName = $_FILES['thumbnail']['name'];
    $thumbTmp  = $_FILES['thumbnail']['tmp_name'];
    move_uploaded_file($thumbTmp, "upload/$thumbName");

    // Upload File PDF
    $fileName = $_FILES['file']['name'];
    $fileTmp  = $_FILES['file']['tmp_name'];
    move_uploaded_file($fileTmp, "upload/$fileName");

    // Insert database
    $sql = "
        INSERT INTO arsip 
        (judul, deskripsi, kategori, penulis, tanggal, file_path, thumbnail, user_id)
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8)
    ";

    pg_query_params($conn, $sql, [
        $judul,
        $deskripsi,
        $kategori,
        $penulis,
        $tanggal,
        $fileName,
        $thumbName,
        $user_id
    ]);

    header("Location: arsipTabel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Arsip</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/css/pages/createArsip.css">
</head>

<body>

<div id="header-placeholder"></div>

<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">

        <section class="hero-section-admin">
            <h1>Tambah Arsip</h1>
        </section>

        <section class="form-section">

            <form action="" method="POST" enctype="multipart/form-data" class="form-arsip">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Penelitian">Penelitian</option>
                            <option value="Pengabdian">Pengabdian</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Penulis</label>
                        <input type="text" name="penulis" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" required>
                    </div>

                    <div class="form-group">
                        <label>File PDF</label>
                        <input type="file" name="file" accept="application/pdf" required>
                    </div>

                    <div class="form-group">
                        <label>Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/png, image/jpeg" required>
                    </div>

                    <div class="form-group full">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" required></textarea>
                    </div>

                </div>

                <input type="hidden" name="user_id" value="1">

                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="arsipTabel.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>
</body>
</html>
