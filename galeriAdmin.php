<?php 
include "koneksi.php";

// Query Agenda
$q_agenda = pg_query($conn, "SELECT agenda_id, hari_tgl, judul, deskripsi FROM agenda ORDER BY hari_tgl DESC");

// Query Dokumentasi
$q_dok = pg_query($conn, "SELECT dokumentasi_id, judul, media_path FROM dokumentasi ORDER BY dokumentasi_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Admin - Network & Cyber Security Lab</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebar.css">
    <link rel="stylesheet" href="assets/css/pages/galeriAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div id="header-placeholder"></div>

    <div class="layout">
        <aside class="sidebar">
            <div id="sidebar-placeholder"></div>
        </aside>

        <main class="content">

            <!-- ===================== AGENDA ===================== -->
            <section class="hero-section-admin">
                <h1>Galeri</h1>
            </section>

            <section class="agenda-section-admin">
                <h2 class="section-title">Agenda Mendatang</h2>
                <div class="agenda-wrapper">

                    <button class="agenda-nav agenda-prev"><i class="fa-solid fa-chevron-left"></i></button>

                    <div class="agenda-container">
                        
                        <?php while($row = pg_fetch_assoc($q_agenda)) : ?>
                        <div class="agenda-card">
                            <h3><?= htmlspecialchars($row['judul']) ?></h3>
                            <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                            <ul>
                                <li>Tanggal: <?= date("d M Y", strtotime($row['hari_tgl'])) ?></li>
                            </ul>
                        </div>
                        <?php endwhile; ?>

                    </div>

                    <button class="agenda-nav agenda-next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>

                <div class="agenda-button-wrapper">
                    <a href="tabelAgenda.php">
                        <button class="kelola-button">Kelola</button>
                    </a>
                </div>
            </section>


            <!-- ===================== DOKUMENTASI ===================== -->
            <section class="dokumentasi-section-admin">
                <h2 class="section-title">Dokumentasi</h2>
                <div class="dokumentasi-container">

                    <?php while($dok = pg_fetch_assoc($q_dok)) : ?>
                    <div class="dokumentasi-card">
                        <div class="dokumentasi-image" 
                             style="background-image: url('uploads/<?= htmlspecialchars($dok['media_path']) ?>');">
                        </div>

                        <div class="dokumentasi-info">
                            <h3><?= htmlspecialchars($dok['judul']) ?></h3>
                            <p>Dokumentasi kegiatan terbaru</p>
                        </div>
                    </div>
                    <?php endwhile; ?>

                </div>

                <div class="pagination">
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                </div>

                <div class="dokumentasi-button-wrapper">
                    <a href="tabelDokumentasi.php">
                        <button class="kelola-button">Kelola</button>
                    </a>
                </div>
            </section>

        </main>
    </div>

    <script src="assets/js/headerSidebar.js"></script>

    <script>
        const agendaPrev = document.querySelector('.agenda-prev');
        const agendaNext = document.querySelector('.agenda-next');
        const agendaContainer = document.querySelector('.agenda-container');

        if (agendaPrev && agendaNext && agendaContainer) {
            agendaPrev.addEventListener('click', () => {
                agendaContainer.scrollBy({ left: -350, behavior: 'smooth' });
            });

            agendaNext.addEventListener('click', () => {
                agendaContainer.scrollBy({ left: 350, behavior: 'smooth' });
            });
        }
    </script>
</body>
</html>
