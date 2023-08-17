<?php
include 'inc/database.php';
include 'inc/header.php';

if (isset($_GET['e'])) {
    switch ($_GET['e']) {
        case 0:
            $usernameErr = 'That login is occupied, please choose another!';
            break;
        case 1:
            $passwordErr = 'Passwords does not matches!';
            break;
        case 2:
            $success = 'Now you can login into your account!';
            break;
        case 3:
            $passwordErr = 'Something went wrong, please try again later!';
            break;
        case 4:
            $loginErr = 'Login and password does not match!';
            break;
    }
}

$sql = "SELECT * FROM product_list WHERE is_active = 1 LIMIT 0, 20";
$product_list = $conn->query($sql);


?>

    <main>
        <?php if (!isset($_SESSION['username'])): ?>
            <div class="login-container">
                <div class="form-wrapper">
                    <form action="inc/login.php" method="post">
                        <h3>Please enter your login and password</h3>
                        <input type="text" name="username" placeholder="login" required>
                        <input type="password" name="password" placeholder="password" required>
                        <?php if (isset($loginErr)) echo "<label>$loginErr</label>" ?>
                        <button type="submit" name="login">Login</button>
                    </form>
                </div>
                <div class="form-wrapper">
                    <form action="inc/login.php" method="post">
                        <h3>Or you can register your new account</h3>
                        <input type="text" name="username" placeholder="login" required>
                        <?php if (isset($usernameErr)) echo "<label>$usernameErr</label>" ?>
                        <input type="password" name="password" placeholder="password" required>

                        <input type="password" name="passwordRepeat" placeholder="repeat password" required>
                        <?php if (isset($passwordErr)) echo "<label>$passwordErr</label>" ?>
                        <?php if (isset($success)) echo "<label>$success</label>" ?>
                        <button type="submit" name="register">Register</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="item-list">
                <?php while ($item = $product_list->fetch_assoc()): ?>
                    <div class="product-container">
                        <a href="product_page.php?id=<?= $item['id'] ?>">
                            <img height="150" width="150"
                                 src="photo/<?= $item['photo'] ?>"
                                 alt="img of product">
                            <h3><?= $item['product_name'] ?>
                                <?= $item['price'] . 'грн' ?></h3>
                        </a>
                        <a href="product_page.php?id=<?= $item['id'] ?>">
                            <button>Переглянути детальніше</button>
                        </a>
                        <a href="product_request.php?id=<?= $item['id'] ?>">
                            <button>Залишити заявку</button>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </main>

<?php include 'inc/footer.php';