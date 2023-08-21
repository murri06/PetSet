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

$records = $conn->query("SELECT * FROM product_list WHERE is_active = 1");
$num_products = $records->num_rows;

$start_item = 0;
$limit_items = 20;
$num_pages = ceil($num_products / $limit_items);

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS) - 1;
    $start_item = $page * $limit_items;
}

$sql = "SELECT * FROM product_list WHERE is_active = 1 LIMIT $start_item, $limit_items";
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
                        <a href="product_page.php?id=<?= $item['id'] ?><?php if (isset($page)) echo "&page=" . $page + 1; else echo "&page=1" ?>">
                            <img height="250" width="250"
                                 src="photo/<?= $item['photo'] ?>"
                                 alt="img of product">
                            <h3><?= $item['product_name'] ?>
                                <?= $item['price'] . ' <span>₴</span>' ?></h3>
                        </a>
                        <a href="product_page.php?id=<?= $item['id'] ?><?php if (isset($page)) echo "&page=" . $page + 1; else echo "&page=1" ?>">
                            <button>Переглянути детальніше</button>
                        </a>
                        <a href="product_request.php?id=<?= $item['id'] ?><?php if (isset($page)) echo "&page=" . $page + 1; else echo "&page=1" ?>">
                            <button>Залишити заявку</button>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="page-info">
                <h3>Перегляд сторінки <?php if (isset($page)) echo $page + 1; else echo '1' ?>
                    з <?= $num_pages ?></h3>
            </div>
            <div class="page-selector">
                <a href="?page=1">First</a>
                <?php if (isset($page) && $page > 0): ?>
                    <a href="?page=<?= $page ?>">Previous</a>
                <?php else: ?>
                    <a class="inactive">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1;
                           $i <= $num_pages;
                           $i++): ?>
                    <a href="?page=<?= $i ?>"
                       class="<?php if (isset($page) && $i == $page + 1) echo 'inactive' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if (!isset($page)): ?>
                    <a href="?page=2">Next</a>
                <?php elseif ($page + 1 >= $num_pages): ?>
                    <a class="inactive">Next</a>
                <?php else: ?>
                    <a href="?page=<?= $page + 2 ?>">Next</a>
                <?php endif; ?>
                <a href="?page=<?= $num_pages ?>">Last</a>
            </div>
        <?php endif; ?>
    </main>

<?php include 'inc/footer.php';