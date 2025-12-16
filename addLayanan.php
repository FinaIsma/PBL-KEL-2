<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jenis     = trim($_POST['jenis']);
    $nama      = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);

    $editor = $_SESSION['user_id'];

    $sql = "
        INSERT INTO layanan (jenis, nama, deskripsi, user_id)
        VALUES ($1, $2, $3, $4)
    ";

    $result = pg_query_params($conn, $sql, [
        $jenis,
        $nama,
        $deskripsi,
        $editor
    ]);

    if ($result) {
        header("Location: tabelLayanan.php");
        exit;
    } else {
        $error = "Gagal menambahkan data ke database!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Layanan</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
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
.form-add textarea:focus,
.form-add select:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 4px rgba(255, 184, 77, 0.6);
}

.form-add textarea {
    resize: vertical;
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
        <h1>Tambah Layanan</h1>
    </section>

    <?php if (!empty($error)): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">
        <div class="form-wrapper">

        <form action="" method="POST" class="form-add">

            <label for="jenis">Jenis Layanan</label>
            <select name="jenis" id="jenis" required>
                <option value="" disabled selected>-- Pilih Jenis --</option>
                <option value="Konsultasi">Konsultasi</option>
                <option value="Peminjaman">Peminjaman</option>
            </select>

            <label for="nama">Nama Layanan</label>
            <input type="text" id="nama" name="nama" required>

            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>

            <button type="submit" class="btn-submit">Simpan</button>
            <a href="tabelLayanan.php" class="btn-cancel">Batal</a>

        </form>

        </div>
    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
