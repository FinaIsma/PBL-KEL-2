<?php 
include("db.php");

// Variabel untuk menyimpan pesan kesalahan jika ada
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Ambil data dari form
    $jenis       = trim($_POST['jenis']);
    $nama        = trim($_POST['nama']);
    $deskripsi   = trim($_POST['deskripsi']);
    $editor      = 1;

    $sql = "INSERT INTO layanan (jenis, nama, deskripsi, user_id)
            VALUES ('$jenis', '$nama', '$deskripsi', $editor)";

    if (pg_query($conn, $sql)) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Layanan</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">

<style>

/* ====== LAYOUT SAMAKAN DENGAN TABEL ====== */
.content {
    margin-left: 220px;
    padding: 0;
    width: calc(100% - 220px);
    min-height: 100vh;
    background: #fff;
}

.hero-section-admin {
    padding-left: 80px; /* Geser ke kanan */
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

.form-peta-jalan textarea {
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
            <h1>Tambah Layanan</h1>
        </section>

        <?php if (!empty($error)): ?>
            <div style="padding: 10px 80px; color: red; font-weight: bold;"><?php echo $error; ?></div>
        <?php endif; ?>

        <section class="form-section">

            <form action="layananTambah.php" method="POST" enctype="multipart/form-data" class="form-add">

    <!-- Jenis Layanan -->
    <label for="jenis">Jenis Layanan</label>
    <select name="jenis" id="jenis" required>
        <option value="" disabled selected>-- Pilih Jenis --</option>
        <option value="Konsultasi">Konsultasi</option>
        <option value="Peminjaman">Peminjaman</option>
    </select>

    <!-- Nama Layanan -->
    <label for="nama">Nama Layanan</label>
    <input type="text" id="nama" name="nama" placeholder="Masukkan nama layanan..." required>

    <!-- Deskripsi -->
    <label for="deskripsi">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi layanan..." required></textarea>

    <!-- Tombol -->
    <button type="submit" name="submit" class="btn-submit">Simpan</button>
    <a href="tabelLayanan.php" class="btn-cancel">Batal</a>

</form>

        </section>

    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>