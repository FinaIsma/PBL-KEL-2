<?php
include("koneksi.php");

if (!isset($_GET['peta_id'])) {
    die("ID Peta Jalan tidak ditemukan.");
}
$peta_id = $_GET['peta_id'];

$query = "SELECT * FROM peta_jalan WHERE peta_id = $1";
$result = pg_query_params($koneksi, $query, [$peta_id]);
$peta = pg_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $tahun = $_POST['tahun'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_POST['user_id']; 

    $file_path = $peta['file_path']; 
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

    $updateQuery = "UPDATE peta_jalan SET judul=$1, tahun=$2, deskripsi=$3, file_path=$4, user_id=$5 WHERE peta_id=$6";
    $updateResult = pg_query_params($koneksi, $updateQuery, [$judul, $tahun, $deskripsi, $file_path, $user_id, $peta_id]);

    if ($updateResult) {
        header("Location: petaJalanTabel.php");
        exit;
    } else {
        echo "Gagal mengupdate data: " . pg_last_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peta Jalan</title>
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
            <h1>Edit Peta Jalan</h1>
        </section>

        <section class="form-section">
            <form action="" method="POST" enctype="multipart/form-data" class="form-peta-jalan">

                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($peta['judul']) ?>" required>

                <label for="tahun">Tahun</label>
                <input type="number" id="tahun" name="tahun" value="<?= htmlspecialchars($peta['tahun']) ?>" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($peta['deskripsi']) ?></textarea>

                <label for="file">Upload File (PDF)</label>
                <?php if(!empty($peta['file_path'])): ?>
                    <p>File saat ini: <a href="<?= htmlspecialchars($peta['file_path']) ?>" target="_blank">Lihat File</a></p>
                <?php endif; ?>
                <input type="file" id="file" name="file" accept="application/pdf">

                <input type="hidden" name="user_id" value="<?= htmlspecialchars($peta['user_id']) ?>">

                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="petaJalanTabel.php" class="btn-cancel">Batal</a>

            </form>
        </section>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>