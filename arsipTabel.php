<<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("backend/config.php"); 

$showConfirm = false;
$confirmData = null;

if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    try {
        $stmt = $db->prepare("SELECT judul FROM arsip WHERE arsip_id = :id");
        $stmt->execute(['id' => $delete_id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $showConfirm = true;
            $confirmData = [
                'id'    => $delete_id,
                'judul' => $row['judul']
            ];
        }
    } catch (PDOException $e) {
        die("Error DB: " . $e->getMessage());
    }
}

$search = $_GET['search'] ?? "";

try {
    if ($search !== "") {
        $stmt = $db->prepare("
            SELECT * FROM arsip
            WHERE judul ILIKE :q
               OR kategori ILIKE :q
               OR deskripsi ILIKE :q
               OR penulis ILIKE :q
               OR tanggal::text ILIKE :q
               OR file_path ILIKE :q
               OR thumbnail ILIKE :q
               OR user_id::text ILIKE :q
            ORDER BY arsip_id ASC
        ");
        $stmt->execute(['q' => "%$search%"]);
    } else {
        $stmt = $db->query("SELECT * FROM arsip ORDER BY arsip_id ASC");
    }

    $arsipList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Arsip</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/utils.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/pages/navbar.css">
    <link rel="stylesheet" href="assets/css/pages/sidebarr.css">
    <link rel="stylesheet" href="assets/css/pages/tabelCRUD.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .thumb-preview {
            width: 60px !important;
            height: 74px !important;
            object-fit: cover !important;
            border-radius: 6px !important;
        }
        .file-preview {
            max-width: 120px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }
    </style>
</head>

<body>

<div id="header"></div>
<div id="sidebar"></div>

<main class="content">

    <div class="top-bar-page">

        <a href="arsipAdmin.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="title-container">

            <div class="title-row">
                <h1 class="title-page">Arsip</h1>

                <a href="createArsip.php" class="btn-add" data-add>
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>

            <div class="search-row">
                <div class="search-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input 
                        type="text"
                        class="search-input"
                        placeholder="Search"
                        name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        oninput="window.location='?search=' + this.value"
                    >
                </div>
            </div>

        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Penulis</th>
                    <th>Tanggal</th>
                    <th>File</th>
                    <th>User ID</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $no = 1;
                foreach ($arsipList as $row) : 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= htmlspecialchars($row['penulis'] ?? "") ?></td>
                    <td><?= date("d M Y", strtotime($row['tanggal'])) ?></td>


                    <td>
                        <?php if ($row['file_path']) : ?>
                            <canvas 
                                class="pdf-thumb"
                                data-pdf="upload/<?= $row['file_path'] ?>">
                            </canvas>
                        <?php else : ?>
                            <span>Tidak ada</span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($row['user_id']) ?></td>

                    <td>
                        <div class="action-buttons">
                            <a href="editArsip.php?id=<?= $row['arsip_id'] ?>" class="btn-action btn-edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="arsipTabel.php?delete_id=<?= $row['arsip_id'] ?>" 
                            class="btn-action btn-delete"><i class="fa-solid fa-trash"></i>
                        </a>

                        </div>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <div class="btn-save-wrapper">
        <a href="arsipAdmin.php" class="btn-save" data-save>Simpan</a>
    </div>

</main>

<script src="assets/js/sidebarHeader.js"></script>
<script type="module" src="assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

document.querySelectorAll(".pdf-thumb").forEach(canvas => {
    const pdfUrl = canvas.dataset.pdf;

    pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
        pdf.getPage(1).then(page => {
            const viewport = page.getViewport({ scale: 0.12 });
            const ctx = canvas.getContext("2d");

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            page.render({
                canvasContext: ctx,
                viewport: viewport
            });
        });
    });
});
</script>

</body>
<?php if ($showConfirm && $confirmData): ?>
<style>

    .pdf-thumb {
    width: 10px;
    height: 12px;
    border-radius: 4px;
    border: 1px solid #ddd;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-box {
        background: #fff;
        padding: 28px 36px;
        border-radius: 14px;
        text-align: center;
        min-width: 340px;
        box-shadow: 0 10px 30px rgba(0,0,0,.25);
    }

    .modal-box p {
        margin-bottom: 22px;
        font-size: 15px;
    }

    .modal-form {
        display: flex;
        justify-content: center;
        gap: 14px;
    }

    .btn-modal {
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }

    .btn-danger {
        background: #e74c3c;
        color: #fff;
    }

    .btn-cancel {
        background: #bdc3c7;
        color: #2c3e50;
        text-decoration: none;
        line-height: 40px;
    }

</style>

<div class="modal-overlay">
    <div class="modal-box">
        <p>
            Yakin ingin menghapus arsip<br>
            <strong><?= htmlspecialchars($confirmData['judul']) ?></strong>?
        </p>

        <form method="POST" action="deleteArsip.php" class="modal-form">
            <input type="hidden" name="id" value="<?= $confirmData['id'] ?>">
            <button type="submit" class="btn-modal btn-danger">
                Ya, Hapus
            </button>
            <a href="arsipTabel.php" class="btn-modal btn-cancel">
                Batal
            </a>
        </form>
    </div>
</div>
<?php endif; ?>

</html>
