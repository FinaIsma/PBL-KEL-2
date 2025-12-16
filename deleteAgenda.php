<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

include("backend/config.php"); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    $stmt = $db->prepare("DELETE FROM agenda WHERE agenda_id = ?");
    $stmt->execute([$id]);

    header("Location: tabelAgenda.php");
    exit;
}
