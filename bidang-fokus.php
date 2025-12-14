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
    <title>Bidang Fokus - Network & Cyber Security Laboratory</title>
    
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <!-- <link rel="stylesheet" href="assets/css/layout.css"> -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body:not(.no-header) {
            padding-top: 80px;
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
            box-sizing: border-box;
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
        .navbar {
        background-color: #0E1F43; /* biru */
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

.navbar.scrolled {
    background-color: #ffffff !important; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    color: #000;
}

.navbar a {
    color: white;
    transition: color 0.3s ease;
}

.navbar.scrolled a {
    color: #0E1F43; 
}
    </style>
</head>
<body>
    <!-- Navbar Placeholder -->
    <div id="navbar-placeholder"></div>
    
    <!-- Header Section -->
    <div class="header-section">
        <div class="header-content">
            <h1>Bidang Fokus</h1>
            <div class="divider"></div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="content-wrapper">
        <?php if (!empty($bidang)): ?>
            <?php foreach ($bidang as $row): ?>
                <div class="focus-column">
                    <!-- Gambar -->
                    <div class="focus-image"><img src="<?= htmlspecialchars(pathGambar($row['gambar'])) ?>"
         alt="<?= htmlspecialchars($row['judul']) ?>"></div>
                    
                    <!-- Konten -->
                    <div class="focus-content">
                        <h2><?= htmlspecialchars($row['judul']) ?></h2>
                        <div class="paragraph-btn-wrapper">
                            <p><?= htmlspecialchars(substr($row['deskripsi'], 0, 120)) ?>...</p>
                            <!-- Detail link -->
                             <a href="guestDetail-bidang.php?id=<?= $row['bidangfokus_id'] ?>" class="focus-btn">
                               <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?>
    </main>
    
    <!-- Footer Placeholder -->
    <div id="footer-placeholder"></div>
    
            <!-- JavaScript -->
   <!-- Load navbar.js dan footer.js eksternal -->
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/footer.js"></script>
    <script type="module" src="assets/js/main.js"></script>

</body>
</html>