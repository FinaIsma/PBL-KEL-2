<?php
session_start();
require_once "../config.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    header("Location: ../../login.php?error=1");
    exit;
}

try {
    $query = $db->prepare("SELECT * FROM public.users WHERE username = :username LIMIT 1");
    $query->execute([":username" => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if ($password === $user['password']) {

            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header("Location: ../../dashboard.php");
            exit;
        }
    }

    header("Location: ../../login.php?error=1");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
