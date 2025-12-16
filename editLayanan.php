<?php
session_start();
include("db.php");

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$error = "";

$id = intval($_GET['id'] ?? 0);
if (!$id) die("ID layanan tidak ditemukan");

$result = pg_query($conn, "SELECT * FROM layanan WHERE layanan_id = $id");
if (!$editData = pg_fetch_assoc($result)) die("Data layanan tidak ditemukan");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jenis     = trim($_POST['jenis']);
    $nama      = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $editor    = $_SESSION['user_id'];

    $sql = "UPDATE layanan 
            SET jenis='$jenis', nama='$nama', deskripsi='$deskripsi', user_id=$editor
            WHERE layanan_id=$id";

    if (pg_query($conn, $sql)) {
        header("Location: tabelLayanan.php");
        exit;
    } else {
        $error = "Gagal memperbarui data layanan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Layanan</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

/* ====== LAYOUT ====== */
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


.logo-area { 
    display: flex; 
    align-items: center; 
    gap: 10px; 
    margin-bottom: 40px; 
}

.lab-title { 
    font-size: 14px; 
    line-height: 1.3; 
}
.lab-title span { font-weight: 400; }

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

.topbar { 
    background: #fff; 
    border-bottom: 1px solid var(--gray-200); 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    padding: 0 24px; 
}

.top-right { 
    font-size: 14px; 
    color: var(--gray-700); 
}

.content {
    margin-left: 220px;  /* sama seperti lebar sidebar */
    padding-top: 100px;
    transform: scale(0.8);
    transform-origin: top left;
    width: calc((100% - 220px) / 0.8); 
    margin-top: -110px !important; /* opsional kalau memang dibutuhkan */
}

.hero-section-admin {
    padding-left: 80px;
}

/* ====== FORM WRAPPER ====== */
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
.form-peta-jalan label {
    font-family: var(--font-body);
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #000;
}

.form-peta-jalan input,
.form-peta-jalan textarea,
.form-peta-jalan select {
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

.form-peta-jalan textarea {
    resize: vertical;
    min-height: 120px;
}

.btn-submit {
    background: var(--secondary);
    color: #000;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
}

.btn-submit:hover {
    background: #FF9A3D;
}

.btn-cancel {
    margin-left: 12px;
    padding: 12px 30px;
    background: #e0e0e0;
    color: #000;
    border-radius: 8px;
    font-size: 16px;
    text-decoration: none;
}

</style>

</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Edit Layanan</h1>
    </section>

    <!-- Alert -->
    <?php if (!empty($error)): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">

        <form method="POST" class="form-peta-jalan">

            <label>Jenis Layanan</label>
            <select name="jenis" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Konsultasi" <?= ($editData['jenis'] === 'Konsultasi') ? 'selected' : '' ?>>Konsultasi</option>
                <option value="Peminjaman" <?= ($editData['jenis'] === 'Peminjaman') ? 'selected' : '' ?>>Peminjaman</option>
            </select>

            <label>Nama Layanan</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($editData['nama']) ?>" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi"><?= htmlspecialchars($editData['deskripsi']) ?></textarea>

            <button type="submit" class="btn-submit">Simpan</button>
            <a href="tabelLayanan.php" class="btn-cancel">Batal</a>

        </form>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
