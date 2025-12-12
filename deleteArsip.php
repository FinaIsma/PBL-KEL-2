<?php
include "koneksi.php";

if (!isset($_GET['arsip_id'])) {
    die("ID tidak ditemukan.");
}

$arsip_id = $_GET['arsip_id'];

$sql = "DELETE FROM arsip WHERE arsip_id = $1";
pg_query_params($conn, $sql, [$arsip_id]);

header("Location: arsipTabel.php");
exit();
