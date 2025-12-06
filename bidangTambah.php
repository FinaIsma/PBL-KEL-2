<?php
include("koneksi-bidang.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_POST['user_id'];

    $gambar = "";
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $target_file;
        }
    }

    $insertQuery = "INSERT INTO bidang_fokus (judul, deskripsi, gambar, user_id) VALUES ($1, $2, $3, $4)";
    $insertResult = pg_query_params($koneksi, $insertQuery, [$judul, $deskripsi, $gambar, $user_id]);

    if ($insertResult) {
        header("Location: tabelBidang.php");
        exit;
    } else {
        echo "Gagal tambah: " . pg_last_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bidang Fokus</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">

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
.content {
    margin-left: 220px;
    padding: 0;
    width: calc(100% - 220px);
    min-height: 100vh;
    background: #fff;
}

.hero-section-admin {
    padding-left: 80px;
}

.form-section {
    padding: 20px 60px;
}

/* ====== FORM ELEMENTS ====== */
.form-add label {
    font-family: var(--font-body);
    font-size: 18px;
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
    font-size: 16px;
    font-family: var(--font-body);
    color: #000;
    background: #f9f9f9;
    outline: none;
    margin-bottom: 22px;
}

.form-add input:focus,
.form-add textarea:focus {
    border-color: #FFB84D;
    box-shadow: 0 0 4px rgba(255, 184, 77, 0.6);
}

.form-add textarea {
    resize: vertical;
}

/* ====== BUTTONS ====== */
.btn-submit {
    background: #FFB84D;
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
<div id="header-placeholder"></div>
<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">
        <section class="hero-section-admin">
            <h1>Tambah Bidang Fokus</h1>
        </section>

        <section class="form-section">
            <!-- Form asli bidangTambah.php tetap dipakai -->
            <form method="POST" enctype="multipart/form-data" class="form-add">
                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>

                <label for="gambar">Upload Gambar</label>
                <input type="file" id="gambar" name="gambar">

                <input type="hidden" name="user_id" value="1"><!-- Sesuaikan dengan user aktif -->

                <button type="submit" class="btn-submit">Tambah</button>
                <a href="tabelBidang.php" class="btn-cancel">Kembali</a>
            </form>
        </section>
    </main>
</div>
<script src="assets/js/headerSidebar.js"></script>
</body>
</html>
