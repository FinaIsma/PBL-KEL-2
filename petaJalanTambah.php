<?php
include("koneksi.php");

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $tahun = $_POST['tahun'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_POST['user_id']; // bisa diganti dengan session user ID

    // Tangani file upload
    $file_path = '';
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        } else {
            echo "Gagal mengunggah file.";
        }
    }

    // Insert data ke database
    $insertQuery = "INSERT INTO peta_jalan (judul, tahun, deskripsi, file_path, user_id) VALUES ($1, $2, $3, $4, $5)";
    $insertResult = pg_query_params($koneksi, $insertQuery, [$judul, $tahun, $deskripsi, $file_path, $user_id]);

    if ($insertResult) {
        header("Location: petaJalanTabel.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . pg_last_error($koneksi);
    }
}
?>

<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peta Jalan</title>

<link rel="stylesheet" href="assets/css/base.css">
<link rel="stylesheet" href="assets/css/pages/navbar.css">
<link rel="stylesheet" href="assets/css/pages/sidebar.css">
<link rel="stylesheet" href="assets/css/pages/petaJalanTambah.css">

</head>
<body>

<div id="header-placeholder"></div>
<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">

        <section class="hero-section-admin">
            <h1>Tambah Peta Jalan</h1>
        </section>

        <section class="form-section">

            <form action="" method="POST" enctype="multipart/form-data" class="form-peta-jalan">

                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" placeholder="Masukkan judul" required>

                <label for="tahun">Tahun</label>
                <input type="number" id="tahun" name="tahun" placeholder="Contoh: 2025" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi..." required></textarea>

                <label for="file">Upload File (PDF)</label>
                <input type="file" id="file" name="file" accept="application/pdf">

                <input type="hidden" name="user_id" value="1">

                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="petaJalanTabel.php" class="btn-cancel">Batal</a>

            </form>
        </section>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>