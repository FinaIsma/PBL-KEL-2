<?php
session_start();
require_once "backend/config.php";

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$error = "";

if (!isset($_GET['id'])) {
    die("ID Bidang Fokus tidak ditemukan.");
}
$id = $_GET['id'];


// ================= AMBIL DATA LAMA =================
try {
    $stmt = $db->prepare(
        "SELECT * FROM bidang_fokus WHERE bidangfokus_id = :id"
    );
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        die("Data tidak ditemukan!");
    }

} catch (PDOException $e) {
    die("Query error: " . $e->getMessage());
}

// ================= UPDATE DATA =================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $user_id   = $_SESSION['user_id'];
    $gambar    = $data['gambar'];

    // ===== UPLOAD GAMBAR =====
    if (!empty($_FILES['gambar']['name'])) {

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name   = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $target_file;
        } else {
            $error = "Gagal upload gambar!";
        }
    }

    // ===== UPDATE DATABASE =====
        if (empty($error)) {
        try {
            $update = $db->prepare("
                UPDATE bidang_fokus
                SET judul = :judul,
                    deskripsi = :deskripsi,
                    gambar = :gambar,
                    user_id = :user_id
                WHERE bidangfokus_id = :id
            ");

            $update->execute([
                'judul'     => $judul,
                'deskripsi' => $deskripsi,
                'gambar'    => $gambar,
                'user_id'   => $user_id,
                'id'        => $id
            ]);

            header("Location: tabelBidang.php");
            exit;

        } catch (PDOException $e) {
            $error = "Gagal memperbarui data: " . $e->getMessage();
        }
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
<link rel="stylesheet" href="assets/css/pages/sidebarr.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== LAYOUT SAMA DENGAN TAMBAH ===== */
main, .content {
    margin-top: 100px;
}

.sidebar {
    width: 220px;
    position: fixed;
    top: 83.5px;
    left: 0;
    height: calc(100vh - 83.5px);
}

.navbar {
    box-shadow: 3px 5px 10px rgba(0,0,0,0.15) !important;
    background: #fff;
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

/* ===== FORM WRAPPER ===== */
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

/* ===== FORM ELEMENTS ===== */
.form-add label {
    font-family: var(--font-body);
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #000;
}

.form-add input,
.form-add textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #999;
    font-size: 13px;
    font-family: var(--font-body);
    color: #000;
    background: #f9f9f9;
    margin-bottom: 22px;
}

.form-add textarea {
    resize: vertical;
    min-height: 100px;
}

/* ===== BUTTONS ===== */
.btn-submit {
    background: var(--secondary);
    color: #000;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(255,184,77,0.3);
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
}

.btn-cancel:hover {
    background: #ccc;
}

/* ALERT ERROR */
.alert-error {
    padding: 14px 20px;
    background: #ffdddd;
    border-left: 5px solid #e74c3c;
    margin-bottom: 20px;
    border-radius: 6px;
}
</style>
</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <section class="hero-section-admin">
        <h1>Edit Bidang Fokus</h1>
    </section>

    <section class="form-section">
        <div class="form-wrapper">

            <?php if (!empty($error)): ?>
                <div class="alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-add">

                <label for="judul">Judul</label>
                <input type="text" id="judul" name="judul"
                       value="<?= htmlspecialchars($data['judul']) ?>" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>

                <label for="gambar">Upload Gambar</label>
                <?php if (!empty($data['gambar'])): ?>
                    <p>Gambar saat ini:</p>
                    <img src="<?= htmlspecialchars($data['gambar']) ?>" width="120">
                <?php endif; ?>
                <input type="file" id="gambar" name="gambar">

                <button class="btn-submit" type="submit">Perbarui</button>
                <a href="tabelBidang.php" class="btn-cancel">Batal</a>

            </form>

        </div>
    </section>

</main>

<script src="assets/js/sidebarHeader.js"></script>
</body>
</html>
