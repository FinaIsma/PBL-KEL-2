<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("backend/config.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM peta_jalan WHERE peta_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
}

header("Location: petaJalanTabel.php");
exit;

