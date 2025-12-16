<?php
require_once __DIR__ . "/backend/config.php";

// --- Ambil data lama ---
if (!isset($_GET['id'])) {
    die("Error: ID agenda tidak ditemukan.");
}

$agenda_id = $_GET['id'];

$stmt = $db->prepare("SELECT * FROM agenda WHERE agenda_id = :id");
$stmt->execute(['id' => $agenda_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Error: Data tidak ditemukan.");
}

if (isset($_POST['submit'])) {

    $hari_tgl  = $_POST['hari_tgl'];
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    $update = $db->prepare("
        UPDATE agenda
        SET hari_tgl = :hari_tgl,
            judul = :judul,
            deskripsi = :deskripsi
        WHERE agenda_id = :id
    ");

    $update->execute([
        'hari_tgl'  => $hari_tgl,
        'judul'     => $judul,
        'deskripsi' => $deskripsi,
        'id'        => $agenda_id
    ]);

    header("Location: tabelAgenda.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Agenda</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<style>

/* === LAYOUT SAMA DENGAN CREATE AGENDA === */
main, .content {
    margin-top: 100px;
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

/* === FORM SECTION === */
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

/* === FORM INPUT === */
.form-add label {
    font-family: var(--font-body);
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}

.form-add input,
.form-add textarea {
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

/* === BUTTON === */
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
        <h1>Edit Agenda</h1>
    </section>

    <section class="form-section">

        <div class="form-wrapper">
            <form action="" method="POST" class="form-add">

                <label>Hari / Tanggal</label>
                <input type="date" name="hari_tgl" value="<?= $data['hari_tgl'] ?>" required>

                <label>Judul Agenda</label>
                <input type="text" name="judul" value="<?= $data['judul'] ?>" required>

                <label>Deskripsi</label>
                <textarea name="deskripsi" required><?= $data['deskripsi'] ?></textarea>

                <button type="submit" name="submit" class="btn-submit">Update</button>
                <a href="tabelAgenda.php" class="btn-cancel">Batal</a>

            </form>
        </div>

    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>

</body>
</html>
