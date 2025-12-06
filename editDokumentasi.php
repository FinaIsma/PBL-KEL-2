<?php
include "koneksi.php";

// --- Ambil ID ---
if (!isset($_GET['id'])) {
    die("Error: ID dokumentasi tidak ditemukan.");
}

$dokumentasi_id = $_GET['id'];

// Ambil data lama
$result = pg_query_params($conn, "SELECT * FROM dokumentasi WHERE dokumentasi_id = $1", [$dokumentasi_id]);
$data = pg_fetch_assoc($result);

if (!$data) {
    die("Error: Data tidak ditemukan.");
}

// ====== UPDATE DATA ======
if (isset($_POST['submit'])) {

    $judul = trim($_POST['judul']);
    $media_path = $data['media_path']; // default pakai yang lama

    // Upload gambar baru jika ada
    if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {

        $uploadDir = "uploads/dokumentasi/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . "_" . basename($_FILES['media']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {
            // Hapus gambar lama jika ada
            if (!empty($data['media_path']) && file_exists($data['media_path'])) {
                unlink($data['media_path']);
            }
            $media_path = $targetPath;
        } else {
            die("Gagal mengupload file baru.");
        }
    }

    // Update data ke database
    $sql = "UPDATE dokumentasi SET judul = $1, media_path = $2 WHERE dokumentasi_id = $3";
    $resultUpdate = pg_query_params($conn, $sql, [$judul, $media_path, $dokumentasi_id]);

    if (!$resultUpdate) {
        die("Query Error: " . pg_last_error($conn));
    } else {
        header("Location: tabelDokumentasi.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokumentasi</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/css/pages/createArsip.css">

    <style>
        #preview {
            width: 150px;
            border-radius: 8px;
            display: block;
            margin-top: 10px;
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
        <div class="hero-section-admin">
            <h1>Edit Dokumentasi</h1>
        </div>

        <section class="form-section">
            <form action="" method="POST" enctype="multipart/form-data" class="form-arsip">

                <div class="form-grid">

                    <!-- Judul -->
                    <div class="form-group">
                        <label>Judul Dokumentasi</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>
                    </div>

                    <!-- Gambar Lama -->
                    <div class="form-group">
                        <label>Gambar Saat Ini</label>
                        <img id="preview" src="<?= $data['media_path'] ?>" alt="Preview">
                    </div>

                    <!-- Upload Baru -->
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
</div>

<script src="assets/js/headerSidebar.js"></script>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>
