<?php
include 'database.php';
include 'header.php';

if (isset($_POST['login'])) {
    $login = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $res = $conn->query("SELECT * FROM admin_login WHERE `username` = '$login' LIMIT 1");
    if ($res->num_rows > 0) {
        $res = $res->fetch_assoc();
        if (password_verify($password, $res['password'])) {
            $_SESSION['admin'] = $res['username'];
            header("Location: /petset/admin_panel.php");
            exit();

        } else $err = 'Логін та пароль не співпадають!';

    } else $err = 'Логін та пароль не співпадають!';
}


?>
    <main>
        <div class="login-container">
            <div class="form-wrapper">
                <form action="" method="post">
                    <h3>Введіть логін та пароль для входу як адміністратор</h3>
                    <input type="text" name="username" placeholder="логін" required>
                    <input type="password" name="password" placeholder="пароль" required>
                    <?php if (isset($err)) echo "<label>$err</label>" ?>
                    <button type="submit" name="login">Увійти</button>
                </form>
            </div>
        </div>
    </main>

<?php include 'footer.php';
$conn->close();