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

    if (!isset($_GET['id'])) {
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
    } else {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sql = "UPDATE `product_list` SET `product_name`='$product_name',`description`='$description',
                          `price`='$price',`article`='$article',`is_active`='$is_active' WHERE id = '$id'";
        if ($conn->query($sql))
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success');
    }

}

if (!isset($_GET['form']) || $_GET['form'] == 1)
    $sql = "SELECT * FROM product_list";
elseif ($_GET['form'] == 2) {
    $sql = "SELECT * FROM product_requests";
    if (isset($_GET['filter']) && $_GET['filter'] != '') {
        $filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sql = "SELECT * FROM product_requests WHERE status = '$filter'";
    }
}

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
                                    <th>Оновити</th>
                                    <th>Видалити</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($item = $res->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td>
                                            <a href="product_page.php?id=<?= $item['id'] ?>"><?= $item['product_name'] ?></a>
                                        </td>
                                        <td><?= $item['description'] ?></td>
                                        <td><?= $item['price'] . '<span>₴</span>' ?></td>
                                        <td><img height="100" width="100" src="photo/<?= $item['photo'] ?>"
                                                 alt="img of product"></td>
                                        <td><?= $item['article'] ?></td>
                                        <td><?= $item['is_active'] ?></td>
                                        <td><a href="?form=1&id=<?= $item['id'] ?>"><i class="bi bi-pencil"></i></a>
                                        </td>
                                        <td>
                                            <a href="inc/product_delete.php?id=<?= $item['id'] ?>"
                                               onclick="return confirm('Ви впевнені що хочете видалити даний продукт?')">
                                                <i class="bi bi-trash"></i></a></td>
                                    </tr>
                                <?php endwhile;
                                unset($res); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h3>У списку немає продуктів</h3>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_GET['id'])) {
                        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $sql = "SELECT * FROM product_list WHERE id = '$id'";
                        $res = $conn->query($sql)->fetch_assoc();
                    } ?>
                    <div class="form-container">
                        <form action="" method="post" enctype="multipart/form-data">

                            <label>Назва продукту</label>
                            <input type="text" name="product_name" required value="<?= $res['product_name'] ?? '' ?>">

                            <label>Опис продукту</label>
                            <textarea name="description" required><?= $res['description'] ?? '' ?></textarea>

                            <label>Ціна продукту</label>
                            <input type="text" name="price" value="<?= $res['price'] ?? '' ?>">

                            <?php if (!isset($_GET['id'])): ?>
                                <label>Завантажте фото продукту</label>
                                <input type="file" name="photo"
                                       accept="image/jpeg, image/png, .svg" required>
                            <?php endif; ?>

                            <label>Артикул продукту</label>
                            <input type="text" name="article" required value="<?= $res['article'] ?? '' ?>">

                            <label for="is_active">Продукт активний для покупки?</label>
                            <select name="is_active" id="is_active" required>
                                <option value="0">Неактивний</option>
                                <option value="1" selected>Активний</option>
                            </select>
                            <div class="flex-container">
                                <button type="submit" name="submit">Завантажити</button>
                                <?php if (isset($_GET['id'])): ?>
                                    <a class="button" href="admin_panel.php?form=1">Назад</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif ($_GET['form'] == 2): ?>
                <div class="request-container">
                    <div class="table-wrapper">
                        <h2>Список заявок</h2>
                        <div class="filter-form">
                            <h3>Фільтрування по статусу</h3>
                            <form class="filter" action="" method="get">
                                <input type="hidden" name="form" value="2">
                                <select name="filter">
                                    <option value="">Усі</option>
                                    <option value="Новий" <?= isset($_GET['filter']) && $_GET['filter'] == 'Новий' ? 'selected' : '' ?>>
                                        Новий
                                    </option>
                                    <option value="Обробляється" <?= isset($_GET['filter']) && $_GET['filter'] == 'Обробляється' ? 'selected' : '' ?>>
                                        Обробляється
                                    </option>
                                    <option value="Готовий до доставки" <?= isset($_GET['filter']) && $_GET['filter'] == 'Готовий до доставки' ? 'selected' : '' ?>>
                                        Готовий до доставки
                                    </option>
                                    <option value="Доставляється" <?= isset($_GET['filter']) && $_GET['filter'] == 'Доставляється' ? 'selected' : '' ?>>
                                        Доставляється
                                    </option>
                                    <option value="Доставлений" <?= isset($_GET['filter']) && $_GET['filter'] == 'Доставлений' ? 'selected' : '' ?>>
                                        Доставлений
                                    </option>
                                    <option value="Завершений" <?= isset($_GET['filter']) && $_GET['filter'] == 'Завершений' ? 'selected' : '' ?>>
                                        Завершений
                                    </option>
                                    <option value="Скасований" <?= isset($_GET['filter']) && $_GET['filter'] == 'Скасований' ? 'selected' : '' ?>>
                                        Скасований
                                    </option>
                                </select>
                                <button type="submit">Оновити</button>
                            </form>
                        </div>
                        <?php if ($res->num_rows > 0): ?>
                            <table class="admin-products">
                                <thead>
                                <tr>
                                    <td>ID заявки</td>
                                    <td>ID продукту</td>
                                    <td>Ім'я клієнта</td>
                                    <td>Номер телефону</td>
                                    <td>Коментар</td>
                                    <td>Коментар менеджера</td>
                                    <td>Статус</td>
                                    <td>Оновити</td>
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
                                        <td>
                                            <a href="tel:<?= $item['client_phone'] ?>">+380<?= $item['client_phone'] ?></a>
                                        </td>
                                        <td><?= $item['comment'] ?></td>
                                        <td><?= $item['admin_comment'] ?></td>
                                        <td><?= $item['status'] ?></td>
                                        <td><a href="?form=2&id=<?= $item['id'] ?>"><i class="bi bi-pencil"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h3>У списку немає заявок</h3>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_GET['id'])):
                        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                        $sql = "SELECT * FROM product_requests WHERE id = '$id'";
                        $request = $conn->query($sql)->fetch_assoc();
                        ?>
                        <div class="request-update">
                            <h3>Оновлення даних заявки № <?= $request['id'] ?></h3>
                            <form action="inc/request_update.php" method="post">
                                <label>Коментар менеджера</label>
                                <textarea name="comment" cols="30" rows="10"><?= $request['admin_comment'] ?></textarea>
                                <label>Статус заявки</label>
                                <select name="status">
                                    <option value="Новий" <?= $request['status'] == 'Новий' ? 'selected' : '' ?>>
                                        Новий
                                    </option>
                                    <option value="Обробляється" <?= $request['status'] == 'Обробляється' ? 'selected' : '' ?>>
                                        Обробляється
                                    </option>
                                    <option value="Готовий до доставки" <?= $request['status'] == 'Готовий до доставки' ? 'selected' : '' ?>>
                                        Готовий до доставки
                                    </option>
                                    <option value="Доставляється" <?= $request['status'] == 'Доставляється' ? 'selected' : '' ?>>
                                        Доставляється
                                    </option>
                                    <option value="Доставлений" <?= $request['status'] == 'Доставлений' ? 'selected' : '' ?>>
                                        Доставлений
                                    </option>
                                    <option value="Завершений" <?= $request['status'] == 'Завершений' ? 'selected' : '' ?>>
                                        Завершений
                                    </option>
                                    <option value="Скасований" <?= $request['status'] == 'Скасований' ? 'selected' : '' ?>>
                                        Скасований
                                    </option>
                                </select>
                                <input type="hidden" name="id" value="<?= $request['id'] ?>">
                                <div class="flex-container">
                                    <button type="submit" name="submit">Завершити</button>
                                    <a class="button" href="admin_panel.php?form=2">Назад</a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h2>Вам потрібно увійти як адмін для перегляду інформації!</h2>
        <?php endif; ?>

    </main>
<?php include 'inc/footer.php';
$conn->close();