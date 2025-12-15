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
    $editor     = 1; // session nantinya

    $sql = "UPDATE profil 
            SET kategori='$kategori', judul='$judul', isi='$isi', user_id=$editor
            WHERE profil_id=$id";

    if (pg_query($conn, $sql)) {
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
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="assets/css/CRUDTable.css">

<style>

/* ====== LAYOUT DARI KODE 2 ====== */
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

/* ====== FORM SECTION ====== */
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
    background: #f9f9f9;
    color: #000;
    outline: none;
    margin-bottom: 22px;
}

.form-add textarea {
    resize: vertical;
}

/* ====== BUTTON ====== */
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
}

.btn-cancel:hover {
    background: #ccc;
}

/* ====== ALERT ====== */
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

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Edit Profil</h1>
    </section>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">

        <div class="form-wrapper">

            <form method="POST" class="form-add">

                <label>Jenis Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Sejarah" <?= ($editData['kategori']==='Sejarah')?'selected':'' ?>>Sejarah</option>
                    <option value="Visi"     <?= ($editData['kategori']==='Visi')?'selected':'' ?>>Visi</option>
                    <option value="Misi"     <?= ($editData['kategori']==='Misi')?'selected':'' ?>>Misi</option>
                </select>

                <label>Nama Judul</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($editData['judul']) ?>" required>

                <label>Isi</label>
                <textarea name="isi"><?= htmlspecialchars($editData['isi']) ?></textarea>

                <button type="submit" class="btn-submit">Simpan</button>
                <a href="tabelProfil.php" class="btn-cancel">Batal</a>

            </form>

        </div>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
