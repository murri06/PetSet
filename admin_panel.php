<?php

include 'inc/database.php';
include 'inc/header.php';

if (isset($_POST['submit'])) {

    $product_name = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = (float)$price;
    $article = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_active = filter_input(INPUT_POST, 'is_active', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $filename = $_FILES['photo']['name'];
    $fileTemp = $_FILES['photo']['tmp_name'];

    $exp = explode('.', $filename);
    $extension = end($exp);

    $newFileName = time() . '.' . $extension;
    $target = __DIR__ . '/photo/' . $newFileName;

    if (move_uploaded_file($fileTemp, $target)) {
        $sql = "INSERT INTO product_list(`product_name`, `description`, `price`, `article`, `photo`, `is_active`) 
                VALUES('$product_name', '$description', '$price', '$article', '$newFileName', '$is_active')";
        if ($conn->query($sql))
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success');
    }
}

if (!isset($_GET['form']) || $_GET['form'] == 1)
    $sql = "SELECT * FROM product_list";
elseif ($_GET['form'] == 2)
    $sql = "SELECT * FROM product_requests";

$res = $conn->query($sql);
?>
    <main>
        <?php if (isset($_SESSION['admin'])): ?>
            <form class="form-selector" action="" method="get">
                <select name="form">
                    <option value="1" selected>Продукти</option>
                    <option value="2">Заявки</option>
                </select>
                <button type="submit">Перейти</button>
            </form>

            <?php if (!isset($_GET['form']) || $_GET['form'] == 1): ?>

                <div class="product-admin-container">
                    <div class="table-wrapper">
                        <?php if ($res->num_rows > 0): ?>
                            <table class="admin-products">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Назва продукту</th>
                                    <th>Опис</th>
                                    <th>Ціна</th>
                                    <th>Фото</th>
                                    <th>Артикул</th>
                                    <th>Чи активний</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($item = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['product_name'] ?></td>
                                        <td><?= $item['description'] ?></td>
                                        <td><?= $item['price'] . '<span>₴</span>' ?></td>
                                        <td><img height="100" width="100" src="photo/<?= $item['photo'] ?>"
                                                 alt="img of product"></td>
                                        <td><?= $item['article'] ?></td>
                                        <td><?= $item['is_active'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h3>У списку немає продуктів</h3>
                        <?php endif; ?>
                    </div>
                    <div class="form-container">
                        <form action="" method="post" enctype="multipart/form-data">

                            <label>Назва продукту</label>
                            <input type="text" name="product_name" required>

                            <label>Опис продукту</label>
                            <textarea name="description" required></textarea>

                            <label>Ціна продукту</label>
                            <input type="text" name="price">

                            <label>Завантажте фото продукту</label>
                            <input type="file" name="photo" accept="image/jpeg, image/png, .svg" required>

                            <label>Артикул продукту</label>
                            <input type="text" name="article" required>

                            <label for="is_active">Продукт активний для покупки?</label>
                            <select name="is_active" id="is_active" required>
                                <option value="0">Неактивний</option>
                                <option value="1" selected>Активний</option>
                            </select>
                            <button type="submit" name="submit">Завантажити</button>
                        </form>
                    </div>
                </div>
            <?php elseif ($_GET['form'] == 2): ?>
                <div>
                    <h2>Список заявок</h2>
                    <div class="table-wrapper">
                        <?php if ($res->num_rows > 0): ?>
                            <table class="admin-products">
                                <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>ID продукту</td>
                                    <td>Ім'я клієнта</td>
                                    <td>Номер телефону</td>
                                    <td>Коментар</td>
                                    <td>Статус</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($item = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td>
                                            <a href="product_page.php?id=<?= $item['product_id'] ?>">
                                                <?= $item['product_id'] ?></a>
                                        </td>
                                        <td><?= $item['client_name'] ?></td>
                                        <td><?= $item['client_phone'] ?></td>
                                        <td><?= $item['comment'] ?></td>
                                        <td><?= $item['status'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h3>У списку немає заявок</h3>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h2>Вам потрібно увійти як адмін для перегляду інформації!</h2>
        <?php endif; ?>

    </main>
<?php include 'inc/footer.php';
$conn->close();