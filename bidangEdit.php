<?php
include("koneksi-bidang.php");

// Pastikan ada ID
if (!isset($_GET['id'])) {
    die("ID Bidang Fokus tidak ditemukan.");
}
$id = $_GET['id'];

// Ambil data lama
$result = pg_query_params($koneksi, "SELECT * FROM bidang_fokus WHERE bidangfokus_id = $1", [$id]);
if (!$result) {
    die("Query gagal: " . pg_last_error($koneksi));
}
$data = pg_fetch_assoc($result);
if (!$data) {
    die("Data tidak ditemukan!");
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $user_id = $_POST['user_id'];

    // Default gambar lama
    $gambar = $data['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $target_file;
        }
    }

    // Update data
    $updateQuery = "UPDATE bidang_fokus SET judul=$1, deskripsi=$2, gambar=$3, user_id=$4 WHERE bidangfokus_id=$5";
    $updateResult = pg_query_params($koneksi, $updateQuery, [$judul, $deskripsi, $gambar, $user_id, $id]);

    if ($updateResult) {
        header("Location: tabelBidang.php");
        exit;
    } else {
        echo "Gagal update: " . pg_last_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Bidang Fokus</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">

<style>
.navbar {
background-color: #fff;
}
.navbar a,
.navbar span,
.navbar i,
.navbar .brand-title,
.navbar .brand-sub {
    color: #000 !important;
}
    .content { 
    margin-left:220px; 
    width:calc(100% - 220px); 
    min-height:100vh; 
    background:#fff; 
    }
    .hero-section-admin { 
    padding-left:80px; 
    }
    .form-section { 
    padding:20px 
    60px; }
    .form-add label { 
    font-size:18px; 
    font-weight:600; 
    margin-bottom:6px; 
    display:block; }
    .form-add input, .form-add textarea { 
    width:100%; 
    padding:12px 15px; 
    border-radius:8px; 
    border:1px solid #999; 
    margin-bottom:22px; 
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
            <h1>Edit Bidang Fokus</h1>
        </section>

        <section class="form-section">
            <form method="POST" enctype="multipart/form-data" class="form-add">
                
                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>

                <label for="gambar">Upload Gambar</label>
                <?php if (!empty($data['gambar'])): ?>
                    <p>Gambar saat ini:</p>
                    <img src="<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar lama" width="100">
                <?php endif; ?>
                <input type="file" id="gambar" name="gambar">

                <input type="hidden" name="user_id" value="<?= htmlspecialchars($data['user_id']) ?>">

                <button type="submit" class="btn-submit">Perbarui</button>
                <a href="tabelBidang.php" class="btn-cancel">Batal</a>
            </form>
        </section>
    </main>
</div>
<script src="assets/js/headerSidebar.js"></script>
</body>

</html>