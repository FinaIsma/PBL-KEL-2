<?php
include("db.php");

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    die("ID sarpras tidak ditemukan");
}

$result = pg_query($conn, "SELECT * FROM sarana_prasarana WHERE sarpras_id = $id");
if (!$row = pg_fetch_assoc($result)) {
    die("Data tidak ditemukan");
}

$success = "";
$error = "";
$mediaName = $row['media_path'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $editor    = 1;

    // === UPLOAD MEDIA BARU ===
    if (!empty($_FILES['media']['name'])) {

        $ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed)) {
            $error = "Format file tidak diizinkan.";
        } else {

            $uploadDir = __DIR__ . '/assets/img/media/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newMediaName = "sarpras_" . time() . "." . $ext;
            $targetPath   = $uploadDir . $newMediaName;

            if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {

                if ($mediaName && file_exists($mediaName)) {
                    unlink($mediaName);
                }

                $mediaName = "assets/img/media/" . $newMediaName;

            } else {
                $error = "Gagal mengupload media!";
            }
        }
    }

    // === UPDATE DATA ===
    if ($error === "") {

        $sql = "UPDATE sarana_prasarana 
                SET judul='$judul', deskripsi='$deskripsi', media_path='$mediaName', user_id=$editor
                WHERE sarpras_id=$id";

        if (pg_query($conn, $sql)) {
            header("Location: tabelSarpras.php");
            exit;
        } else {
            $error = "Gagal memperbarui data!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Sarana & Prasarana</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="assets/css/CRUDTable.css">

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
    color: #000;
    background: #f9f9f9;
    outline: none;
    margin-bottom: 22px;
}

.form-add input:focus,
.form-add textarea:focus {
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
        <h1>Edit Sarana & Prasarana</h1>
    </section>

    <?php if ($error): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">
        <div class="form-wrapper">

            <form method="POST" enctype="multipart/form-data" class="form-add">

                <label>Judul</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($row['judul']) ?>" required>

                <label>Deskripsi</label>
                <textarea name="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></textarea>

                <label>Media Saat Ini</label>
                <div class="media-preview">
                <?php if ($row['media_path'] && file_exists($row['media_path'])): ?>
                    <?php if (preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $row['media_path'])): ?>
                        <img src="<?= htmlspecialchars($row['media_path']) ?>" alt="Media lama">
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($row['media_path']) ?>" target="_blank">Lihat file lama</a>
                    <?php endif; ?>
                <?php endif; ?>
                </div>

                <label>Upload Media Baru (Opsional)</label>
                <input type="file" name="media">

                <button class="btn-submit" type="submit">Simpan</button>
                <a href="tabelSarpras.php" class="btn-cancel">Batal</a>

            </form>

        </div>
    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
