<?php
require_once __DIR__ . "/backend/config.php";

try {
    $stmt = $db->prepare("
        SELECT * 
        FROM bidang_fokus 
        ORDER BY bidangfokus_id ASC
    ");
    $stmt->execute();
    $bidang = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}

function pathGambar($gambar) {
    if (!$gambar) return 'assets/img/default-image.jpg';
    if (str_starts_with($gambar, 'uploads/')) return $gambar;
    return 'uploads/' . $gambar;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bidang Fokus</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
.bidangHero{
    background: var(--primary);
    color: #fff;
    padding: var(--space-7);
    padding-top: 150px;
    padding-bottom: 120px;
    margin-top: -120px;
}

.bidangHero-container{
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.bidangHero-left{
    padding-left: 0;
}

.bidangHero-left h1{
    font-size: 50px;
    line-height: 1.1;
    font-weight: 800;
    color: white;
}

.bidang-wrapper{
    padding: var(--space-7);
    padding-top: 120px;
    padding-bottom: 140px;
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
    margin-left: 50px !important;
    margin-right: 50px !important;
}

.focus-column{
    display: flex;
    gap: var(--space-6);
    background: var(--primary);
    border-radius: var(--radius-lg);
    padding: var(--space-6);
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: .3s ease;
}

.focus-column:hover{
    transform: translateY(-6px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
}

.focus-image{
    width: 300px;
    flex-shrink: 0;
}

.focus-image img{
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: var(--radius-md);
    background: #fff;
}

.focus-content{
    flex: 1;
}

.focus-content h2{
    font-size: 1.6rem;
    margin-bottom: var(--space-3);
    color: white;
}

.paragraph-btn-wrapper{
    display: flex;
    justify-content: space-between;
    gap: var(--space-4);
}

.focus-btn{
    background: var(--secondary);
    padding: 10px 14px;
    border-radius: var(--radius-md);
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
    transition: .3s ease;
}

.focus-btn:hover{
    background: #e0a800;
    transform: translateX(4px);
}

.navbar:not(.scrolled) .nav-menu a,
.navbar:not(.scrolled) .nav-brand span,
.navbar:not(.scrolled) .dropdown-btn{
    color: #fff !important;
}

.navbar:not(.scrolled) .dropdown-menu a{
    color: #000 !important;
}

.navbar.scrolled{
    background: rgba(255,255,255,0.98) !important;
    backdrop-filter: blur(6px);
}

.navbar.scrolled .nav-menu a,
.navbar.scrolled .nav-brand span,
.navbar.scrolled .dropdown-btn{
    color: var(--primary) !important;
}

@media (max-width: 1024px) {
    .bidangHero-left h1 {
        font-size: 42px;
    }

    .bidang-wrapper {
        margin-left: 24px !important;
        margin-right: 24px !important;
        padding-top: 100px;
        padding-bottom: 120px;
    }

    .focus-image {
        width: 260px;
    }
}

@media (max-width: 768px) {
    .bidangHero {
        padding-top: 130px;
        padding-bottom: 100px;
    }

    .bidangHero-left h1 {
        font-size: 34px;
    }

    .bidang-wrapper {
        margin-left: 16px !important;
        margin-right: 16px !important;
        gap: var(--space-5);
    }

    .focus-column {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .focus-image {
        width: 100%;
    }

    .focus-image img {
        height: 220px;
    }

    .paragraph-btn-wrapper {
        flex-direction: column;
        align-items: center;
        gap: var(--space-3);
    }
}

@media (max-width: 480px) {
    .bidangHero-left h1 {
        font-size: 26px;
    }

    .bidang-wrapper {
        padding-top: 80px;
        padding-bottom: 100px;
        margin-left: 12px !important;
        margin-right: 12px !important;
    }

    .focus-column {
        padding: var(--space-5);
        gap: var(--space-4);
    }

    .focus-content h2 {
        font-size: 1.25rem;
    }

    .focus-image img {
        height: 180px;
    }

    .focus-btn {
        width: 100%;
        text-align: center;
    }
}

</style>
</head>

<body>

<div id="navbar-placeholder"></div>
<script src="assets/js/navbar.js"></script>

<section class="bidangHero">
    <div class="bidangHero-container">
        <div class="bidangHero-left">
            <h1>Bidang Fokus</h1>
        </div>
    </div>
</section>

<main class="bidang-wrapper">
<?php foreach ($bidang as $row): ?>
    <div class="focus-column">
        <div class="focus-image">
            <img src="<?= htmlspecialchars(pathGambar($row['gambar'])) ?>"
                 alt="<?= htmlspecialchars($row['judul']) ?>">
        </div>

        <div class="focus-content">
            <h2><?= htmlspecialchars($row['judul']) ?></h2>

            <div class="paragraph-btn-wrapper">
                <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 140)) ?>...</p>
                <!-- <a href="guestDetail-bidang.php?id=<?= $row['bidangfokus_id'] ?>"
                   class="focus-btn">
                    <i class="fa-solid fa-arrow-right"></i>
                </a> -->
            </div>
        </div>
    </div>
<?php endforeach; ?>
</main>

<div id="footer-placeholder"></div>
<script src="assets/js/footer.js"></script>
<script type="module" src="assets/js/main.js"></script>

</body>
</html>
