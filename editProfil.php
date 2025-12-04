<?php 
include("db.php");

$id = intval($_GET['id'] ?? 0);
if (!$id) die("ID layanan tidak ditemukan");

// Ambil data lama
$result = pg_query($conn, "SELECT * FROM profil WHERE profil_id = $id");
if (!$editData = pg_fetch_assoc($result)) die("Data layanan tidak ditemukan");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kategori   = trim($_POST['kategori']);
    $judul      = trim($_POST['judul']);
    $isi        = trim($_POST['isi']);
    $editor    = 1; // bisa diganti session

    $sql = "UPDATE profil 
            SET kategori='$kategori', judul='$judul', isi='$isi', user_id=$editor
            WHERE profil_id=$id";

    if (pg_query($conn, $sql)) {
        // Setelah update berhasil, langsung kembali ke tabel
        header("Location: tabelProfil.php");
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
<title>Edit Profil</title>

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
    padding-left: 80px;
}

/* ====== FORM SECTION ====== */
.form-section {
    padding: 20px 60px;
}

/* ====== FORM FIELDS ====== */
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

.alert-error {
    padding: 12px;
    border-radius: 8px;
    background: #ffdddd;
    border-left: 6px solid #e74c3c;
    margin-bottom: 15px;
    font-size: 15px;
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
            <h1>Edit Profil</h1>
        </section>

        <section class="form-section">

            <?php if ($error): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="form-peta-jalan">

                <label>Jenis Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Sejarah" <?= ($editData['kategori'] === 'Sejarah') ? 'selected' : '' ?>>Sejarah</option>
                    <option value="Visi" <?= ($editData['kategori'] === 'Visi') ? 'selected' : '' ?>>Visi</option>
                    <option value="Misi" <?= ($editData['kategori'] === 'Misi') ? 'selected' : '' ?>>Misi</option>
                </select>

                <label>Nama Judul</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($editData['judul']) ?>" required>

                <label>Isi</label>
                <textarea name="isi"><?= htmlspecialchars($editData['isi']) ?></textarea>

                <button type="submit" class="btn-submit">Simpan</button>
                <a href="tabelProfil.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>

