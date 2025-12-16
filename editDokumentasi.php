<?php
session_start();
require_once "backend/config.php";

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Error: ID dokumentasi tidak ditemukan.");
}

$dokumentasi_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM dokumentasi WHERE dokumentasi_id = :id");
$stmt->execute(['id' => $dokumentasi_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Error: Data tidak ditemukan.");
}

if (isset($_POST['submit'])) {

    $judul = trim($_POST['judul']);
    $media_path = $data['media_path'];

    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = "uploads/dokumentasi/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];

        if (!in_array($ext, $allowed)) {
            die("Format file tidak diizinkan.");
        }

        $fileName   = "dokumentasi_" . time() . "." . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {

            if (!empty($data['media_path']) && file_exists($data['media_path'])) {
                unlink($data['media_path']);
            }

            $media_path = $targetPath;
        } else {
            die("Gagal upload file.");
        }
    }

    $stmt = $db->prepare("
        UPDATE dokumentasi
        SET judul = :judul,
            media_path = :media_path,
            user_id = :user_id
        WHERE dokumentasi_id = :id
    ");

    $stmt->execute([
        'judul'      => $judul,
        'media_path' => $media_path,
        'user_id'    => $user_id,
        'id'         => $dokumentasi_id
    ]);

    header("Location: tabelDokumentasi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Dokumentasi</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">

    <style>
        /* ====== LAYOUT (dari kode 2) ====== */
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


.form-wrapper {
    background: #fff;
    border-radius: 12px;
    padding: 30px 40px;
    box-shadow: 0 5px 20px rgba(10, 6, 1, 0.15);
    border: 1px solid #ddd;
}

        /* ===== FORM (dari kode 2) ===== */
        .form-section {
            padding: 20px 60px;
        }

        .form-arsip label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .form-arsip input,
        .form-arsip textarea {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #999;
            font-size: 13px;
            background: #f9f9f9;
            margin-bottom: 22px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .form-group.full {
            grid-column: span 2;
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

        .btn-cancel {
            margin-left: 12px;
            padding: 12px 30px;
            background: #e0e0e0;
            border-radius: 8px;
            text-decoration: none;
            color: #000;
        }

        /* Preview */
        #preview {
            width: 150px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>

</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Edit Dokumentasi</h1>
    </section>

    <section class="form-section">
        <form action="" method="POST" enctype="multipart/form-data" class="form-arsip">

            <div class="form-grid">

                <div class="form-group">
                    <label>Judul Dokumentasi</label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Gambar Saat Ini</label>
                    <img id="preview" src="<?= $data['media_path'] ?>" alt="Preview">
                </div>

                <div class="form-group">
                    <label>Ganti Gambar (Opsional)</label>
                    <input type="file" name="media" accept="image/*" onchange="previewImage(event)">
                </div>

            </div>

            <button type="submit" name="submit" class="btn-submit">Update</button>
            <a href="tabelDokumentasi.php" class="btn-cancel">Batal</a>

        </form>
    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>
