<?php
require_once "backend/config.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul     = trim($_POST['judul']);
    $tanggal   = $_POST['tanggal'];
    $deskripsi = trim($_POST['deskripsi']);
    $editor    = 1;

    if ($judul === "" || $tanggal === "") {
        $error = "Judul dan tanggal wajib diisi.";
    } else {

        try {
            $sql = "INSERT INTO agenda (judul, hari_tgl, deskripsi, user_id)
                    VALUES (:judul, :hari_tgl, :deskripsi, :editor)";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':judul', $judul);
            $stmt->bindParam(':hari_tgl', $tanggal);
            $stmt->bindParam(':deskripsi', $deskripsi);
            $stmt->bindParam(':editor', $editor);
            $stmt->execute();

            header("Location: tabelAgenda.php");
            exit;

        } catch (PDOException $e) {
            $error = "Gagal menyimpan agenda: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Agenda</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">

<style>

/* ====== LAYOUT (SAMA PERSIS ADD PENGELOLA) ====== */
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
    margin-left: 220px;
    padding-top: 100px;
    transform: scale(0.8);
    transform-origin: top left;
    width: calc((100% - 220px) / 0.8);
    margin-top: -110px !important;
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

/* ====== FORM ELEMENTS (SAMA ADD PENGELOLA) ====== */
.form-add label {
    font-family: var(--font-body);
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-add input,
.form-add textarea,
.form-add select {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #999;
    font-size: 13px;
    background: #f9f9f9;
    margin-bottom: 22px;
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
}

.btn-cancel {
    margin-left: 12px;
    padding: 12px 30px;
    background: #e0e0e0;
    color: #000;
    border-radius: 8px;
    font-size: 16px;
    text-decoration: none;
}

</style>

</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Buat Agenda</h1>
    </section>

    <!-- ALERT jika ada -->
    <?php if (!empty($success)): ?>
        <div style="padding: 10px 80px; color: green; font-weight: bold;"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div style="padding: 10px 80px; color: red; font-weight: bold;"><?= $error ?></div>
    <?php endif; ?>

    <section class="form-section">

        <!-- KONTEN FORM KAMU (TIDAK AKU UBAH APA-APA) -->
        <div class="form-wrapper">

            <form method="POST" enctype="multipart/form-data" class="form-add">

                <!-- Konten original createAgenda kamu masuk di sini -->
                <!-- =============================== -->
                <!--        FORM AGENDA KAMU        -->
                <!-- =============================== -->

                <label>Judul Agenda</label>
                <input type="text" name="judul" required>

                <label>Tanggal</label>
                <input type="date" name="tanggal" required>

                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="4"></textarea>

                <button class="btn-submit" type="submit">Simpan</button>
                <a href="tabelAgenda.php" class="btn-cancel">Batal</a>

            </form>

        </div>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
