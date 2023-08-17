<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PetSet</title>
    <link rel="stylesheet" href="/petset/inc/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<header>
    <div class="header-wrapper">
        <h1><a href="/petset/index.php">PetSet</a></h1>
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