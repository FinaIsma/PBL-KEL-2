<?php
include "koneksi.php";

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    $hari_tgl  = $_POST['hari_tgl'];
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $user_id   = $_POST['user_id']; // Hidden input seperti file Arsip

    // Insert ke database
    $sql = "
        INSERT INTO agenda (hari_tgl, judul, deskripsi, user_id)
        VALUES ($1, $2, $3, $4)
    ";

    pg_query_params($conn, $sql, [
        $hari_tgl,
        $judul,
        $deskripsi,
        $user_id
    ]);

    header("Location: tabelAgenda.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Agenda</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/css/pages/createAgenda.css">
</head>

<body>

<div id="header-placeholder"></div>

<div class="layout">

    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">

        <section class="hero-section-admin">
            <h1>Tambah Agenda</h1>
        </section>

        <section class="form-section">
            <form action="" method="POST" class="form-agenda">

                <div class="form-grid">

                    <!-- Hari / Tanggal -->
                    <div class="form-group">
                        <label>Hari / Tanggal</label>
                        <input type="date" name="hari_tgl" required>
                    </div>

                    <!-- Judul -->
                    <div class="form-group">
                        <label>Judul Agenda</label>
                        <input type="text" name="judul" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group full">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" required></textarea>
                    </div>

                </div>
                <input type="hidden" name="user_id" value="1">

                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="tabelAgenda.php" class="btn-cancel">Batal</a>

            </form>
        </section>

    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>
