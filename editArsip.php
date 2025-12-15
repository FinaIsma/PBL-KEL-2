<?php
session_start();
require_once "backend/config.php";

/* ===== CEK ID ===== */
if (!isset($_GET['id'])) {
    die("Error: ID arsip tidak ditemukan.");
}

$arsip_id = (int) $_GET['id'];

/* ===== AMBIL DATA LAMA ===== */
$stmt = $db->prepare("SELECT * FROM arsip WHERE arsip_id = :id");
$stmt->execute(['id' => $arsip_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Error: Data tidak ditemukan.");
}

/* ===== PROSES UPDATE ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul     = trim($_POST['judul']);
    $kategori  = trim($_POST['kategori']);
    $penulis   = trim($_POST['penulis']);
    $tanggal   = $_POST['tanggal'];
    $deskripsi = trim($_POST['deskripsi']);

    try {
        $sql = "
            UPDATE arsip SET
                kategori  = :kategori,
                judul     = :judul,
                deskripsi = :deskripsi,
                penulis   = :penulis,
                tanggal   = :tanggal
        ";

        $params = [
            'kategori'  => $kategori,
            'judul'     => $judul,
            'deskripsi' => $deskripsi,
            'penulis'   => $penulis,
            'tanggal'   => $tanggal
        ];

        /* ===== UPDATE FILE PDF (OPSIONAL) ===== */
        if (!empty($_FILES['file']['name'])) {

            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                die("File harus PDF");
            }

            $uploadDir = "upload/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $newFileName = "arsip_" . time() . ".pdf";
            move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $newFileName);

            // (opsional) hapus file lama
            if (!empty($data['file_path']) && file_exists($uploadDir . $data['file_path'])) {
                unlink($uploadDir . $data['file_path']);
            }

            $sql .= ", file_path = :file_path";
            $params['file_path'] = $newFileName;
        }

        $sql .= " WHERE arsip_id = :id";
        $params['id'] = $arsip_id;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        header("Location: arsipTabel.php");
        exit;

    } catch (PDOException $e) {
        die("Gagal update data: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Arsip</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
        <h1>Edit Arsip</h1>
    </section>

    <?php if (!empty($success)): ?>
        <div style="padding: 10px 80px; color: green; font-weight: bold;"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">

        <form method="POST" enctype="multipart/form-data" class="form-add">

            <label>Judul Arsip</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>

            <label>Kategori</label>
            <select name="kategori" required>
                <option value="Penelitian" <?= $data['kategori']=="Penelitian" ? "selected" : "" ?>>Penelitian</option>
                <option value="Pengabdian" <?= $data['kategori']=="Pengabdian" ? "selected" : "" ?>>Pengabdian</option>
            </select>

            <label>Penulis</label>
            <input type="text" name="penulis" value="<?= htmlspecialchars($data['penulis']) ?>" required>

            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required>

            <label>File PDF</label>

            <?php if ($data['file_path']): ?>
                <p>PDF saat ini: <strong><?= $data['file_path'] ?></strong></p>
            <?php endif; ?>

            <input type="file" name="file">

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>

            <button type="submit" name="submit" class="btn-submit">Simpan Perubahan</button>
            <a href="arsipTabel.php" class="btn-cancel">Batal</a>

        </form>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
