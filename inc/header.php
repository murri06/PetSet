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
        <h2><a href="/petset/index.php">PetSet</a></h2>
        <div class="header-login">
            <?php if (isset($_SESSION['admin'])): ?>
                <a href="/petset/admin_panel.php">Admin Panel</a>
                <a href="inc/logout.php">Logout</a>
            <?php else: ?>
                <a href="/petset/inc/admin.php">Admin login</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])): ?>
                <h3>Welcome, <?= $_SESSION['username'] ?> <a href="inc/logout.php">Logout</a></h3>
            <?php endif; ?>

        </div>
    </div>
</header>