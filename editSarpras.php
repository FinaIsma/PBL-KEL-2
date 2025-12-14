<?php
include("db.php");

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    die("ID sarpras tidak ditemukan");
}

// Ambil data lama
$result = pg_query($conn, "SELECT * FROM sarana_prasarana WHERE sarpras_id = $id");
if (!$row = pg_fetch_assoc($result)) {
    die("Data tidak ditemukan");
}

$success = "";
$error = "";
$mediaName = $row['media_path']; // default media lama

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul       = trim($_POST['judul']);
    $deskripsi   = trim($_POST['deskripsi']);
    $editor      = 1; // bisa diganti session

    // ---------- UPDATE MEDIA ----------
    if (!empty($_FILES['media']['name'])) {
        $ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif','pdf','mp4','mp3'];

        if (!in_array($ext, $allowed)) {
            $error = "Format file tidak diizinkan.";
        } else {
            $uploadDir = __DIR__ . '/assets/img/media/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $newMediaName = "sarpras_" . time() . "." . $ext;
            $targetPath = $uploadDir . $newMediaName;

            if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {
                if ($mediaName && file_exists($mediaName)) unlink($mediaName);
                $mediaName = "assets/img/media/" . $newMediaName;
            } else {
                $error = "Gagal mengupload media!";
            }
        }
    }

    // ---------- UPDATE DATABASE ----------
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
.form-peta-jalan select,
.form-peta-jalan input[type="file"] {
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

.alert-error, .alert-success {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 15px;
}

.alert-error {
    background: #ffdddd;
    border-left: 6px solid #e74c3c;
}

.alert-success {
    background: #c8f7c5;
    border-left: 6px solid #27ae60;
}

.media-preview img {
    width: 150px;
    border-radius: 8px;
    margin-bottom: 15px;
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
            <h1>Edit Sarana & Prasarana</h1>
        </section>

        <section class="form-section">

            <?php if ($success): ?>
                <div class="alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-peta-jalan">

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

                <label>Upload Media Baru (opsional)</label>
                <input type="file" name="media">

                <button type="submit" class="btn-submit">Simpan</button>
                <a href="tabelSarpras.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>

