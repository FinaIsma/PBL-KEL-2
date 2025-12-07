<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NCS Lab</title>

    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/login.css">
</head>

<body>

    <div class="login-wrapper">

        <!-- Logo + Title -->
        <div class="login-header">
            <img src="assets/img/Logo JTI.png" alt="Logo" class="lab-logo">
            <div class="lab-text">
                <h3>Network & Cyber Security</h3>
                <span>Laboratorium</span>
            </div>
        </div>

        <!-- FORM CARD -->
        <div class="login-card">
            <h2 class="login-title">Login</h2>

            <?php if (isset($_SESSION['login_error'])): ?>
                <p class="error-msg"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
            <?php endif; ?>

            <form action="backend/auth/login.php" method="POST">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" required>

                <label class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" class="form-input" required>
                    <span id="togglePassword" class="toggle-password">👁</span>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </div>

        <!-- Background Elements -->
        <img src="assets/img/aura.png" class="aura aura-1">
        <img src="assets/img/pixel_cysec.png" class="pixel pixel-1">

        <img src="assets/img/aura.png" class="aura aura-2">
        <img src="assets/img/pixel_cysec.png" class="pixel pixel-2">

        <img src="assets/img/aura.png" class="aura aura-3">
        <img src="assets/img/pixel_cysec.png" class="pixel pixel-3">

    </div>

    <script src="assets/js/login.js"></script>
</body>
</html>
