<?php
include_once 'inc/database.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PetSet</title>
    <link rel="stylesheet" href="inc/style.css">
</head>
<body>

<header>

</header>

<main>
    <?php if (!isset($_SESSION['username'])): ?>
        <div class="login-container">
            <div class="form-wrapper">
                <form action="" method="post">
                    <h3>Please enter your login and password</h3>
                    <input type="text" placeholder="login" required>
                    <input type="password" placeholder="password" required>
                    <button>Login</button>
                </form>
            </div>
            <div class="form-wrapper">
                <form action="" method="post">
                    <h3>Or you can register your new account</h3>
                    <input type="text" placeholder="login" required>
                    <input type="password" placeholder="password" required>
                    <input type="password" placeholder="repeat password" required>
                    <button>Register</button>
                </form>
            </div>
        </div>
    <?php else: ?>

    <?php endif; ?>
</main>

<footer>

</footer>
</body>
</html>
