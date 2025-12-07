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

        // PASSWORD MASIH PLAIN TEXT
        if ($password === $user['password']) {

            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];

            // ⬇️ langsung masuk ke dashboard HTML kamu
            header("Location: ../../dashboard.php");
            exit;
        }

    }

    header("Location: ../../login.php?error=1");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
