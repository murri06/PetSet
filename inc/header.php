<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PetSet</title>
    <link rel="icon" href="/petset/inc/logo128.png">
    <link rel="stylesheet" href="/petset/inc/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<header>
    <div class="header-wrapper">
        <a href="/petset/index.php"><img src="/petset/inc/logo1024.png" alt="company logo" height="80" width="80"></a>
        <div class="header-login">
            <h3>
                <?php if (isset($_SESSION['admin'])): ?>
                    <a href="/petset/admin_panel.php">Адмін панель </a>
                    <a href="inc/logout.php">Вийти </a>
                <?php else: ?>
                    <a href="/petset/inc/admin.php">Адмін логін </a>
                <?php endif; ?>
            </h3>
            <?php if (isset($_SESSION['username'])): ?>
                <h3>Вітаємо, <?= $_SESSION['username'] ?> <a href="inc/logout.php">Вийти</a></h3>
            <?php endif; ?>

        </div>
    </div>
</header>