<?php 
include("db.php");

$success = "";
$error = "";
$mediaName = null;  // wajib ada sebelum dipakai

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul       = trim($_POST['judul']);
    $deskripsi   = trim($_POST['deskripsi']);
    $editor      = 1; // bisa diganti session

    // ---------- PROSES UPLOAD ----------

    if (!empty($_FILES['media']['name'])) {

        if ($_FILES['media']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif','pdf','mp4','mp3'];

            if (!in_array($ext, $allowed)) {
                $error = "Format file tidak diizinkan.";
            } else {
                $uploadDir = __DIR__ . '/assets/img/media/';  // folder baru di img

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // buat folder jika belum ada
}

$mediaName = "sarpras_" . time() . "." . $ext;
$targetPath = $uploadDir . $mediaName;

if (!move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {
    $error = "Gagal mengupload media!";
}

// Path untuk database relatif ke folder project
$mediaDB = "assets/img/media/" . $mediaName;

            }

        } else {
            $error = "Upload error code: " . $_FILES['media']['error'];
        }

    }

    // ---------- INSERT DATABASE ----------
    if ($error === "") {

        $mediaDB = $mediaDB ?? null; // jika tidak upload file, null

        $sql = "INSERT INTO sarana_prasarana (judul, deskripsi, media_path, user_id)
                VALUES ('$judul', '$deskripsi', '$mediaDB', $editor)";

        $query = pg_query($conn, $sql);

        if ($query) {
            header("Location: tabelSarpras.php");
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
<title>Tambah Sarana & Prasarana</title>

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
            <h1>Tambah Sarana & Prasarana</h1>
        </section>

        <!-- Alert -->
        <?php if (!empty($success)): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <section class="form-section">

                <form method="POST" enctype="multipart/form-data" class="form-add">

                    <label>Judul</label>
                    <input type="text" name="judul" required>

                    <label>Deskripsi</label>
                    <textarea name="deskripsi"></textarea>

                    <label>Media (Gambar/Video/PDF)</label>
                    <input type="file" name="media">

                    <button class="btn-submit" type="submit">Simpan</button>
                    <a href="tabelSarpras.php" class="btn-cancel">Batal</a>

                </form>


        </section>

    </main>

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>

