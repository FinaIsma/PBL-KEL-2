<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once "backend/config.php";

try {
    $stmt = $db->query("SELECT * FROM bidang_fokus ORDER BY bidangfokus_id ASC");
    $bidang = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}

function pathGambar($gambar) {
    if (!$gambar) return 'assets/img/default-image.jpg';

    if (str_starts_with($gambar, 'uploads/')) {
        return $gambar;
    }
    return 'uploads/' . $gambar;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bidang Fokus</title>
  <link rel="stylesheet" href="assets/css/base.css">
  <link rel="stylesheet" href="assets/css/pages/navbar.css">
  <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
  <link rel="stylesheet" href="assets/css/pages/bidangfokusAdmin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
  <div id="header">
    </div>
    <div id="sidebar"></div>
    <div class="hero-section-admin">
  <h1>Bidang Fokus</h1>
</div>

    <main class="content-wrapper">
        <?php if (!empty($bidang)): ?>
            <?php foreach ($bidang as $row): ?>
                <div class="focus-column">
            <div class="focus-image"><img src="<?= htmlspecialchars(pathGambar($row['gambar'])) ?>"
         alt="<?= htmlspecialchars($row['judul']) ?>"></div>

                    <div class="focus-content">
                        <h2><?= htmlspecialchars($row['judul']) ?></h2>
                        <div class="paragraph-btn-wrapper">
                            <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 120)) ?>...</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?>

        <div class="back-to-list">
          <a href="tabelBidang.php" class="back-list-btn">Kelola</a>
        </div>
    </main>

  <script src="assets/js/sidebarHeader.js"></script>
  <script type="module" src="assets/js/main.js"></script>

</body>
</html>