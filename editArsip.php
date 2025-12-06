<?php
include "koneksi.php";

if (!isset($_GET['id'])) {
    die("Error: ID arsip tidak ditemukan.");
}

$arsip_id = $_GET['id'];

$result = pg_query_params($conn, "SELECT * FROM arsip WHERE arsip_id = $1", [$arsip_id]);
$data = pg_fetch_assoc($result);

if (!$data) {
    die("Error: Data tidak ditemukan.");
}

if (isset($_POST['submit'])) {

    $judul     = $_POST['judul'];
    $kategori  = $_POST['kategori'];
    $penulis   = $_POST['penulis'];
    $tanggal   = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    // Untuk dynamic query
    $updateThumb = "";
    $updateFile  = "";

    // === Jika thumbnail diganti ===
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumb = $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], "upload/$thumb");

        $updateThumb = ", thumbnail = '$thumb'";
    }

    // === Jika PDF diganti ===
    if (!empty($_FILES['file']['name'])) {
        $file = $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], "upload/$file");

        $updateFile = ", file_path = '$file'";
    }

    // === Query update ===
    $sql = "UPDATE arsip SET 
            kategori = $1,
            judul = $2,
            deskripsi = $3,
            penulis = $4,
            tanggal = $5";

    $params = [$kategori, $judul, $deskripsi, $penulis, $tanggal];

    // Jika update thumbnail
    if (!empty($thumb)) {
        $sql .= ", thumbnail = $" . (count($params) + 1);
        $params[] = $thumb;
    }

    // Jika update file
    if (!empty($file)) {
        $sql .= ", file_path = $" . (count($params) + 1);
        $params[] = $file;
    }

    $sql .= " WHERE arsip_id = $" . (count($params) + 1);
    $params[] = $arsip_id;

    pg_query_params($conn, $sql, $params);


    header("Location: arsipTabel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Arsip</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/createArsip.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
</head>

<body>

<div id="header-placeholder"></div>

<div class="layout">

    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">

        <section class="hero-section-admin">
            <h1>Edit Arsip</h1>
        </section>

        <section class="form-section">
            <form method="POST" enctype="multipart/form-data" class="form-arsip">

                <div class="form-grid">

                    <!-- JUDUL -->
                    <div class="form-group">
                        <label>Judul Arsip</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>
                    </div>

                    <!-- KATEGORI -->
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori" required>
                            <option value="Penelitian" <?= $data['kategori']=="Penelitian" ? "selected" : "" ?>>Penelitian</option>
                            <option value="Pengabdian" <?= $data['kategori']=="Pengabdian" ? "selected" : "" ?>>Pengabdian</option>
                        </select>
                    </div>

                    <!-- PENULIS -->
                    <div class="form-group">
                        <label>Penulis</label>
                        <input type="text" name="penulis" value="<?= htmlspecialchars($data['penulis']) ?>" required>
                    </div>

                    <!-- TANGGAL -->
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required>
                    </div>

                    <!-- THUMBNAIL -->
                    <div class="form-group">
                        <label>Thumbnail</label>

                        <?php if ($data['thumbnail']): ?>
                            <img src="upload/<?= $data['thumbnail'] ?>" 
                                style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 6px; border: 2px solid #e6e6e6; margin: 10px 0;">
                        <?php endif; ?>

                        <input type="file" name="thumbnail">
                    </div>

                    <!-- FILE PDF -->
                    <div class="form-group">
                        <label>File PDF</label>

                        <?php if ($data['file_path']): ?>
                            <p>PDF saat ini: <strong><?= $data['file_path'] ?></strong></p>
                        <?php endif; ?>

                        <input type="file" name="file">
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="form-group full">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                    </div>

                </div>

                <button type="submit" name="submit" class="btn-submit">Simpan Perubahan</button>
                <a href="arsipTabel.php" class="btn-cancel">Batal</a>

            </form>
        </section>

    </main>

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>
