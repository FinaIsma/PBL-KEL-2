<?php
include "koneksi.php";

// --- Ambil data lama ---
if (!isset($_GET['id'])) {
    die("Error: ID agenda tidak ditemukan.");
}

$agenda_id = $_GET['id'];

// Ambil data dari database
$result = pg_query_params($conn, "SELECT * FROM agenda WHERE agenda_id = $1", [$agenda_id]);
$data = pg_fetch_assoc($result);

if (!$data) {
    die("Error: Data tidak ditemukan.");
}

// --- Jika tombol UPDATE ditekan ---
if (isset($_POST['submit'])) {

    $hari_tgl  = $_POST['hari_tgl'];
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "
        UPDATE agenda
        SET hari_tgl=$1, judul=$2, deskripsi=$3
        WHERE agenda_id=$4
    ";

    pg_query_params($conn, $sql, [
        $hari_tgl,
        $judul,
        $deskripsi,
        $agenda_id
    ]);

    header("Location: tabelAgenda.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/css/pages/createArsip.css">
</head>
<body>

<div id="header-placeholder"></div>

<div class="layout">
    <aside class="sidebar">
        <div id="sidebar-placeholder"></div>
    </aside>

    <main class="content">
        <div class="hero-section-admin">
            <h1>Edit Agenda</h1>
        </div>

        <section class="form-section">
            <form action="" method="POST" class="form-arsip">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Hari / Tanggal</label>
                        <input type="date" name="hari_tgl" value="<?= $data['hari_tgl'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Judul Agenda</label>
                        <input type="text" name="judul" value="<?= $data['judul'] ?>" required>
                    </div>

                    <div class="form-group full">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" required><?= $data['deskripsi'] ?></textarea>
                    </div>

                </div>

                <button type="submit" name="submit" class="btn-submit">Update</button>
                <a href="tabelAgenda.php" class="btn-cancel">Batal</a>

            </form>
        </section>
    </main>
</div>

<script src="assets/js/headerSidebar.js"></script>
</body>
</html>
