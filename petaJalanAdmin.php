<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once "backend/config.php"; // ini harus berisi $db (PDO)

$search = $_GET['search'] ?? '';

try {
    if ($search !== '') {
        // Pakai prepared statement biar aman
        $sql = "
            SELECT * 
            FROM peta_jalan
            WHERE judul ILIKE :search
               OR deskripsi ILIKE :search
               OR CAST(tahun AS TEXT) ILIKE :search
            ORDER BY tahun ASC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':search' => "%" . $search . "%"
        ]);
    } else {
        $sql = "SELECT * FROM peta_jalan ORDER BY tahun ASC";
        $stmt = $db->query($sql);
    }

    // Ambil semua data
    $peta = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalan Admin</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="assets/css/pages/petaJalanAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div id="header"></div>
    <div id="sidebar"></div>

        <main class="content">

            <section class="hero-section-admin">
                <h1>Peta Jalan</h1>
            </section>

            <section class="timeline-section-admin">
                <div class="aura-left-bottom"></div>

                <div class="timeline-container-admin">
                    <div class="timeline-circle-top-admin"></div>

                    <?php 
                    $index = 0;
                    foreach ($peta as $row): 
                        $posisi = ($index % 2 == 0) ? "left" : "right";
                    ?>
                        <div class="timeline-item-admin <?= $posisi ?>">
                            <div class="timeline-card-admin">
                                
                                <div class="timeline-icon-admin">
                                    <i class="fa-solid fa-file-lines"></i>
                                </div>

                                <h3><?= htmlspecialchars($row['tahun']) ?> – <?= htmlspecialchars($row['judul']) ?></h3>

                                <p><?= htmlspecialchars($row['deskripsi']) ?></p>

                                <a class="btn-lihat" href="uploads/<?= $row['file_path'] ?>" target="_blank">
                                    <i class="fa-solid fa-download"></i> Lihat File
                                </a>

                            </div>
                        </div>
                    <?php 
                        $index++;
                    endforeach; 
                    ?>

                    <div class="timeline-circle-bottom-admin"></div>

                </div>

                <div class="kelola-button-wrapper">
                    <a href="petaJalanTabel.php" class="kelola-button">Kelola</a>
                </div>

            </section>

        </main>
    
    <script src="assets/js/sidebarHeader.js"></script>

</body>
</html>