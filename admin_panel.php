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


$sql = "SELECT * FROM product_list";
$res = $conn->query($sql);
?>
    <main>
        <?php if (isset($_SESSION['admin'])): ?>
            <div class="product-admin-container">
                <div class="table-wrapper">
                    <?php if ($res->num_rows > 0): ?>
                        <table>
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Photo</th>
                                <th>Article</th>
                                <th>Is Active</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($item = $res->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $item['id'] ?></td>
                                    <td><?= $item['product_name'] ?></td>
                                    <td><?= $item['description'] ?></td>
                                    <td><?= $item['price'] . 'грн' ?></td>
                                    <td><img height="100" width="100" src="photo/<?= $item['photo'] ?>" alt="img of product"></td>
                                    <td><?= $item['article'] ?></td>
                                    <td><?= $item['is_active'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h3>There is no items in a list</h3>
                    <?php endif; ?>
                </div>
                <div class="form-container">
                    <form action="" method="post" enctype="multipart/form-data">

                        <label>Full product name</label>
                        <input type="text" name="product_name" required>

                        <label>Description of the product</label>
                        <input type="text" name="description" required>

                        <label>Price of the product</label>
                        <input type="text" name="price">

                        <label> Upload photo of the product</label>
                        <input type="file" name="photo" accept="image/jpeg, image/png, .svg" required>

                        <label> Article for the product</label>
                        <input type="text" name="article" required>

                        <label for="is_active">Is product active for a purchase?</label>
                        <select name="is_active" id="is_active" required>
                            <option value="0">inactive</option>
                            <option value="1" selected>active</option>
                        </select>
                        <button type="submit" name="submit">Upload</button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <h2>You must be logged in as admin to see this page!</h2>
        <?php endif; ?>

    </main>
<?php include 'inc/footer.php';