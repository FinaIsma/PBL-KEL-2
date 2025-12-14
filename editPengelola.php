<?php
include("db.php");

$id = intval($_GET['id'] ?? 0);

if (!$id) {
    die("ID pengelola tidak ditemukan");
}

// Ambil data lama
$result = pg_query($conn, "SELECT * FROM pengelola_lab WHERE pengelola_id = $id");
if (!$row = pg_fetch_assoc($result)) {
    die("Data tidak ditemukan");
}

$success = "";
$error = "";
$fotoName = $row['foto']; // default foto lama

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama    = trim($_POST['nama']);
    $jabatan = trim($_POST['jabatan']);
    $kontak  = trim($_POST['kontak']);
    $editor  = 1;

    // ---------- UPDATE FOTO ----------
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];

        if (!in_array($ext, $allowed)) {
            $error = "Format file tidak diizinkan.";
        } else {
            $uploadDir = __DIR__ . '/assets/img/pengelola/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $newFotoName = "pengelola_" . time() . "." . $ext;
            $targetPath = $uploadDir . $newFotoName;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetPath)) {
                if (file_exists($fotoName)) unlink($fotoName);
                $fotoName = "assets/img/pengelola/" . $newFotoName;
            } else {
                $error = "Gagal mengupload foto!";
            }
        }
    }

    // ---------- UPDATE DATABASE ----------
    if ($error === "") {
        $sql = "UPDATE pengelola_lab 
                SET nama='$nama', jabatan='$jabatan', kontak='$kontak', foto='$fotoName', user_id=$editor
                WHERE pengelola_id=$id";
        if (pg_query($conn, $sql)) {
            // Setelah update berhasil, langsung kembali ke tabel
            header("Location: tabelPengelola.php");
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
<title>Edit Pengelola Lab</title>

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
.form-peta-jalan select {
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
}

/* FOTO */
.preview-foto {
    margin-bottom: 12px;
}

.preview-foto img {
    width: 120px;
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
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

.alert-success,
.alert-error {
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 15px;
}

.alert-success {
    background: #c8f7c5;
    border-left: 6px solid #27ae60;
}

.alert-error {
    background: #ffdddd;
    border-left: 6px solid #e74c3c;
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
            <h1>Edit Pengelola Lab</h1>
        </section>

        <section class="form-section">

            <?php if ($success): ?>
                <div class="alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-peta-jalan">

                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>

                <label>Jabatan</label>
                <input type="text" name="jabatan" value="<?= htmlspecialchars($row['jabatan']) ?>" required>

                <label>Kontak</label>
                <input type="text" name="kontak" value="<?= htmlspecialchars($row['kontak']) ?>" required>

                <label>Foto</label>

                <?php if ($row['foto'] && file_exists($row['foto'])): ?>
                    <div class="preview-foto">
                        <img src="<?= htmlspecialchars($row['foto']) ?>" alt="Foto lama">
                    </div>
                <?php endif; ?>

                <input type="file" name="foto">

                <button type="submit" class="btn-submit">Simpan</button>
                <a href="tabelPengelola.php" class="btn-cancel">Batal</a>

            </form>

        </section>

    </main>

</div>

<script src="assets/js/headerSidebar.js"></script>

</body>
</html>
