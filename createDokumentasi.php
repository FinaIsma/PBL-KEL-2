<?php
include "koneksi.php";

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    $judul   = $_POST['judul'];
    $user_id = $_POST['user_id'];

    // Upload Gambar
    $mediaName = $_FILES['media_path']['name'];
    $mediaTmp  = $_FILES['media_path']['tmp_name'];

    if ($mediaName) {
        if (!move_uploaded_file($mediaTmp, "upload/$mediaName")) {
            die("Gagal upload file!");
        }
    }

    // Insert ke database
    $sql = "INSERT INTO dokumentasi (judul, media_path, user_id) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $sql, [$judul, $mediaName, $user_id]);

    if (!$result) {
        die("Error query: " . pg_last_error($conn));
    }

    header("Location: tabelDokumentasi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokumentasi</title>

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
            <h1>Tambah Dokumentasi</h1>
        </section>

        <section class="form-section">

            <form action="" method="POST" enctype="multipart/form-data" class="form-arsip">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" required>
                    </div>

                    <div class="form-group">
                        <label>File / Gambar</label>
                        <input type="file" name="media_path" accept="image/png, image/jpeg" required>
                    </div>

                </div>

                <input type="hidden" name="user_id" value="1">

                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="tabelDokumentasi.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>
</body>
</html>
