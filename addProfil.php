<?php 
include("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Nama input disesuaikan dengan form
    $kategori   = trim($_POST['kategori']);
    $judul      = trim($_POST['judul']);
    $isi        = trim($_POST['isi']);
    $editor     = 1; // bisa diganti session login

    // INSERT ke tabel PROFIL (bukan layanan)
    $sql = "INSERT INTO profil (kategori, judul, isi, user_id)
            VALUES ('$kategori', '$judul', '$isi', $editor)";

    if (pg_query($conn, $sql)) {
        header("Location: tabelProfil.php");
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
<title>Tambah Profil</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebar.css">

<style>

/* ====== LAYOUT ====== */
body {
    background: #fff;
}

.content {
    margin-left: 220px;
    padding: 0;
    width: calc(100% - 220px);
    min-height: 100vh;
}

.hero-section-admin {
    padding-left: 80px;
}

/* ====== FORM SECTION ====== */
.form-section {
    padding: 20px 60px;
}

/* ====== FORM ELEMENTS (DISAMAKAN DENGAN PENGELOLA) ====== */
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
.form-add textarea:focus,
.form-add select:focus {
    border-color: #FFB84D;
    box-shadow: 0 0 4px rgba(255, 184, 77, 0.6);
}

.form-add  textarea {
    resize: vertical;
    min-height: 100px;
}

/* ====== BUTTONS (SAMA PERSIS) ====== */
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

/* ALERT ERROR */
.alert-error {
    padding: 14px 20px;
    background: #ffdddd;
    border-left: 5px solid #e74c3c;
    margin-bottom: 20px;
    border-radius: 6px;
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
            <h1>Tambah Profil</h1>
        </section>

        <section class="form-section">

            <?php if (!empty($error)): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="form-add">

                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Sejarah">Sejarah</option>
                    <option value="Visi">Visi</option>
                    <option value="Misi">Misi</option>
                </select>

                <label>Judul</label>
                <input type="text" name="judul" required>

                <label>Isi</label>
                <textarea name="isi"></textarea>

                <button class="btn-submit" type="submit">Simpan</button>
                <a href="tabelProfil.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>

