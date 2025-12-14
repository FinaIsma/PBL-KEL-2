<?php
include("koneksi-bidang.php");
if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id = $_GET['id'];
    $result = pg_query($koneksi, "SELECT * FROM bidang_fokus WHERE bidangfokus_id = $id");

    if (!$result) {
        die("Query gagal: " . pg_last_error($koneksi));
    }

    $data = pg_fetch_assoc($result);

    if (!$data) {
        echo "Data tidak ditemukan!";
        exit;
    }

} else {

    $result = pg_query($koneksi, "SELECT * FROM bidang_fokus ORDER BY bidangfokus_id ASC LIMIT 1");
    if (!$result) {
        die("Query gagal: " . pg_last_error($koneksi));
    }

    $data = pg_fetch_assoc($result);

    if (!$data) {
        echo "Tidak ada data bidang fokus!";
        exit;
    }

    $id = $data['bidangfokus_id'];
}

$result2 = pg_query($koneksi, "
    SELECT * FROM bidang_fokus 
    WHERE bidangfokus_id != $id 
    ORDER BY bidangfokus_id ASC
    LIMIT 2
");

$others = [];
while ($row = pg_fetch_assoc($result2)) {
    $others[] = $row;
}
$section2 = $others[0] ?? null;
$section3 = $others[1] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Bidang Fokus</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <!-- CSS Lokal -->
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

    body {
    background: white url('assets/img/aura.png');
    background-repeat: space;
    background-size: 700px 700px;
    background-position: center;
    margin: 0;
    padding: 0;
    font: Arial sans-serif;
    }

/* Header Section */
.header-section {
    background: white url('assets/img/image 3.png') no-repeat center center;
    background-size: cover;
    color: white;
    padding: 4rem 2rem;
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    margin-left: 220px; 
    padding: 20px;
    width: calc(100% - 220px);
}

.header-section h1 {
    font-size: 60px;
    margin: 0;
    text-align: center;
    font-weight: 700;
    color: #fff;
}

.back-btn {
    position: absolute;
    left: 2rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5rem;
    cursor: pointer;
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 5px;
    color: #fff;
    text-decoration: none;
}

  .content-wrapper {
    width: 100%;
    max-width: 1000px;
    margin-left:  250px;
    }

    /* Detail Title */
    .detail-title {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 3rem;
      text-align: center;
      color: #000;
      border-bottom: 3px solid #FEBE11;
      padding-bottom: 1rem;
    }

/* Detail Section */
.detail-section {
    display: flex;
    gap: 24px;
    margin: 50px 0;
    align-items: center;
    width: calc(100% - 220px); 
    margin-left: 10px; 
    padding: 20px 0;    
}

.detail-section.reverse {
    flex-direction: row-reverse;
<<<<<<< HEAD
    margin-left: 5px; 
    padding: 20px;
    width: calc(80% - 100px);
=======
    margin-left: 100px; 
    padding: 20px;
    width: calc(100% - 220px);
>>>>>>> a212e903ca5457e05e3ce3484cc3b1894b6a22e1
}

.detail-text {
    flex: 1;
    text-align: left;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    }

    .detail-text h2 {
      font-size: 1.8rem;
      color: #2c3e50;
      margin-bottom: 1rem;
      text-align: left !important;
    }

    .detail-text p {
      line-height: 1.7;
      margin-bottom: 16px !important;
      color: #555;
      font-size: 1.1rem;
    }

    .detail-list {
      list-style: none;
      padding-left: 20px;
    }

    .detail-list li {
      margin-bottom: 0.8rem;
      list-style: none;
      display: flex;
      align-items: center;
      gap: 10px !important;
      font-size: 1rem;
    }
    .detail-img img {
      width: 500px;      
      height: 300px;
      object-fit: cover;
      background: #ecf0f1; 
      border-radius: 10px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      border: 3px solid #fff;
    }

      /* Back to List Button */
    .back-to-list {
      text-align: right;
      margin-top: 4rem;
      padding-top: 2rem solid #FEBE11;
    }

    .back-list-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: #FEBE11;
      color: #000;
      padding: 12px 24px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .back-list-btn:hover {
      background: #fff;
      transform: translateX(-5px);
    }

   /* Responsive */
    @media (max-width: 992px) {
      .detail-section {
        flex-direction: column;
        gap: 2rem;
      }
      
      .detail-section.reverse {
        flex-direction: column;
      }
      
      .detail-img img {
        height: 250px;
      }
      
      .header-section h1 {
        font-size: 2rem;
      }
      
      .detail-title {
        font-size: 1.6rem;
      }
    }

</style>

</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-container">
      <div class="nav-left">
        <img src="assets/img/Logo JTI.png" alt="Logo" class="nav-logo">
        <div class="nav-brand">
          <span class="brand-title">Network & Cyber Security</span>
          <span class="brand-sub">Laboratorium</span>
        </div>
      </div>
      <ul class="nav-menu">
        <li class="nav-profile"><i class="fa-solid fa-user"></i><span>Admin</span><span>|</span></li>
        <li class="nav-logout"><a href="logout.html">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Detail Header -->
  <div class="header-section">
    <a class="back-btn" href="admin_bidang-fokus.php"><i class="fa-solid fa-arrow-left"></i></a>
    <h1>Nama Bidang Fokus</h1>
</div>

  <!-- Layout Container -->
  <div class="layout"></div>
    <!-- Sidebar -->
    <aside class="sidebar">
      <div id="sidebar-placeholder"></div>
    </aside>

  <div class="content-wrapper">
    <div class="detail-title">Evaluasi Keamanan Jaringan pada Sistem Informasi Akademik</div>

    <!-- Main Content -->
<main class="content">
  <div class="content-wrapper">

<?php
function pathGambar($gambar) {
    if (!$gambar) return 'assets/img/default-image.jpg';
    if (str_starts_with($gambar, 'uploads/')) return $gambar;
    return 'uploads/' . $gambar;
}

// Ambil semua section berdasarkan bidangfokus_id
$result_detail = pg_query(
    $koneksi,
    "SELECT * FROM bidang_fokus ORDER BY bidangfokus_id ASC"
);

$detailSections = pg_fetch_all($result_detail) ?: [];

// Loop tampilkan section selang-seling reverse
$index = 0;
foreach ($detailSections as $section):
    $reverse = ($index % 2 == 1) ? "reverse" : "";
?>
    <div class="detail-section <?= $reverse ?>">
        <div class="detail-text">
            <h2><?= htmlspecialchars($section['judul']) ?></h2>
            <p><?= nl2br(htmlspecialchars($section['deskripsi'])) ?></p>
        </div>

        <div class="detail-img">
            <img src="<?= htmlspecialchars(pathGambar($section['gambar'])) ?>"
                 alt="<?= htmlspecialchars($section['judul']) ?>">
        </div>
    </div>

<?php
$index++;
endforeach;
?>

  </div>

  <!-- Back Button -->
  <div class="back-to-list">
      <a href="tabelBidang.php" class="back-list-btn">Kelola</a>
  </div>
</main>

</div>

  <!-- Load Header & Sidebar JS -->
  <script src="assets/js/headerSidebar.js"></script>
  <script type="module" src="assets/js/main.js"></script>

</body>
</html>
