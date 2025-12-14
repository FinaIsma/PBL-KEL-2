<?php 
include("db.php");

$success = "";
$error = "";
$fotoName = null;  // wajib ada sebelum dipakai

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama    = trim($_POST['nama']);
    $jabatan = trim($_POST['jabatan']);
    $kontak  = trim($_POST['kontak']);
    $editor  = 1; // bisa diganti session

    // ---------- PROSES UPLOAD ----------
    if (!empty($_FILES['foto']['name'])) {

        if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];

            if (!in_array($ext, $allowed)) {
                $error = "Format file tidak diizinkan.";
            } else {
                // folder tujuan (absolut)
                // folder tujuan (relatif terhadap addPengelola.php)
$uploadDir = __DIR__ . '/assets/img/pengelola/';  // perbaikan

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$fotoName = "pengelola_" . time() . "." . $ext;
$targetPath = $uploadDir . $fotoName;

if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
    $error = "Gagal mengupload foto!";
}

// path yang disimpan ke DB
$fotoDB = "assets/img/pengelola/" . $fotoName;

            }
        } else {
            $error = "Upload error code: " . $_FILES['foto']['error'];
        }
    }

    // ---------- INSERT DATABASE ----------
    if ($error === "") {

    $fotoDB = "assets/img/pengelola/" . $fotoName;

    $sql = "INSERT INTO pengelola_lab (nama, jabatan, kontak, foto, user_id)
            VALUES ('$nama', '$jabatan', '$kontak', '$fotoDB', $editor)";

    $query = pg_query($conn, $sql);

    if ($query) {
        // redirect langsung
        header("Location: tabelPengelola.php");
        exit;
    } else {
        $error = "Gagal menambahkan data ke database!";
    }
}

}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengelola Lab</title>

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
            <h1>Tambah Pengelola Lab</h1>
        </section>

        <!-- Alert -->
        <?php if (!empty($success)): ?>
            <div style="padding: 10px 80px; color: green; font-weight: bold;"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
        <?php endif; ?>


        <section class="form-section">

            <form method="POST" enctype="multipart/form-data" class="form-add">

                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>

                <label>Jabatan</label>
                <input type="text" name="jabatan" required>

                <label>Kontak</label>
                <input type="text" name="kontak" required>

                <label>Foto</label>
                <input type="file" name="foto">

                <button class="btn-submit" type="submit">Simpan</button>
                <a href="tabelPengelola.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>

