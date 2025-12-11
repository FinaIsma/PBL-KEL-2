<?php
include("koneksi-bidang.php");
$result = pg_query($koneksi, "SELECT * FROM bidang_fokus ORDER BY bidangfokus_id ASC");
if (!$result) {
    die("Query gagal: " . pg_last_error($koneksi));
}

$bidang = [];
while ($row = pg_fetch_assoc($result)) {
    $bidang[] = $row;
}
function pathGambar($gambar) {
    if (!$gambar) return 'assets/img/default-image.jpg';

    // Jika sudah lengkap path-nya
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
            background-size: 500px 500px;
            background-position: center;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        .header-section {
            background: #0E1F43;
            color: white;
            padding: 0 2rem;
            text-align: center;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 450px;
            margin-left: 220px; 
            padding: 20px;
            width: calc(100% - 220px);
        }
        
        .header-section h1 {
            font-size: 60px !important;
            margin: 0 0 1rem 0;
            text-align: center;
            font-weight: 700;
            color: #fff;
        }
        
        .divider {
            height: 3px;
            width: 100px;
            margin: 2rem auto;
        }
        
        /* Content Wrapper */
        .content-wrapper {
            width: auto;
            margin-right: 40px;
            margin-left: 40px;
            margin-top: 20px;
            margin-bottom: 50px;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            box-sizing: border-box;
        }
        
        /* Fokus Kolom */
        .focus-column {
            display: flex;
            gap: 2rem;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #fff;
            align-items: flex-start;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #0E1F43;
            margin-left: 220px; 
            padding: 40px;
            width: calc(100% - 220px);
        }
        
        .focus-column:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .focus-image {
            flex: 0 0 300px;
            position: relative;
        }
        
        .focus-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 3px solid #3498db;
            box-shadow: 0 4px 12px rgba(154, 142, 142, 0.15);
            background: white !important;
        }
        
        .focus-content {
            flex: 1;
            color: #fff;
        }
        
        .focus-content h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #fff;
        }
        
        .focus-content p {
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        
        .focus-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: #FEBE11;
            color: #0E1F43;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        
        .focus-btn:hover {
            transform: translateX(4px);
            background: #FFD633;
        }
        
        /* Wrapper untuk paragraf dan tombol */
        .paragraph-btn-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        
        .paragraph-btn-wrapper p {
            flex: 1;
            margin: 0;
            color: #fff;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .focus-column {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .focus-image {
                width: 100%;
                flex: none;
            }
            
            .paragraph-btn-wrapper {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .header-section {
                min-height: 300px;
                padding: 2rem;
            }
            
            .header-section h1 {
                font-size: 40px !important;
            }
            
            .content-wrapper {
                margin: 0 20px 30px;
                padding: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                margin: 0 15px 20px;
                padding: 1.5rem;
            }
            
            .focus-column {
                padding: 1.5rem;
            }
            
            .focus-image img {
                height: 180px;
            }
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
        
        @media (max-width: 576px) {
            .header-section {
                min-height: 250px;
                padding: 1.5rem;
            }
            
            .header-section h1 {
                font-size: 32px !important;
            }
            
            .content-wrapper {
                margin: 0 10px 15px;
                padding: 1rem;
            }
            
            .focus-column {
                padding: 1rem;
            }
            
            .focus-image img {
                height: 150px;
            }
            
            .focus-content h2 {
                font-size: 1.25rem;
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
    <h1>Bidang Fokus</h1>
    </div>

 <!-- Layout Container -->
  <div class="layout"></div>
    <!-- Sidebar -->
    <aside class="sidebar">
      <div id="sidebar-placeholder"></div>
    </aside>
    
    <!-- Main Content -->
   <main class="content-wrapper">

    <?php if (!empty($bidang)): ?>
        <?php foreach ($bidang as $row): ?>
            
            <div class="focus-column">
                <div class="focus-image">
                    <img src="<?= htmlspecialchars(pathGambar($row['gambar'])) ?>" 
                         alt="<?= htmlspecialchars($row['judul']) ?>">
                </div>

                <div class="focus-content">
                    <h2><?= htmlspecialchars($row['judul']) ?></h2>

                    <div class="paragraph-btn-wrapper">
                        <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 120)) ?>...</p>

                        <a href="admin_detail-bidang.php?id=<?= $row['bidangfokus_id'] ?>" class="focus-btn">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>

    <div class="back-to-list">
        <a href="tabelBidang.php" class="back-list-btn">Kelola</a>
    </div>
</main>

    
  <!-- Load Header & Sidebar JS -->
  <script src="assets/js/headerSidebar.js"></script>
  <script type="module" src="assets/js/main.js"></script>

</body>
</html>