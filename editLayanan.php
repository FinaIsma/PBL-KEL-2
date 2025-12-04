<?php 
include("db.php");

$id = intval($_GET['id'] ?? 0);
if (!$id) die("ID layanan tidak ditemukan");

// Ambil data lama
$result = pg_query($conn, "SELECT * FROM layanan WHERE layanan_id = $id");
if (!$editData = pg_fetch_assoc($result)) die("Data layanan tidak ditemukan");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $jenis     = trim($_POST['jenis']);
    $nama      = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $editor    = 1;

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
<link rel="stylesheet" href="assets/css/pages/sidebar.css">

<style>

/* ====== LAYOUT ====== */
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



/* ====== FORM WRAPPER (SEPERTI TABLE WRAPPER) ====== */
.form-section {
    padding: 20px 60px;
}

/* ====== FORM ELEMENTS ====== */
.form-peta-jalan label {
    font-family: var(--font-body);
    font-size: 18px;
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
    font-size: 16px;
    font-family: var(--font-body);
    color: #000;
    background: #f9f9f9;
    outline: none;
    margin-bottom: 22px;
}

.form-peta-jalan input:focus,
.form-peta-jalan textarea:focus {
    border-color: #FFB84D;
    box-shadow: 0 0 4px rgba(255, 184, 77, 0.6);
}

.form-peta-jalan textarea {
    resize: vertical;
    min-height: 120px;
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
            <h1>Edit Layanan</h1>
        </section>

        <section class="form-section">



                <?php if (!empty($error)): ?>
                    <div class="alert-error"><?= $error ?></div>
                <?php endif; ?>

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

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>
