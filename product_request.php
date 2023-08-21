<?php
include 'inc/database.php';
include 'inc/header.php';

if (isset($_POST['submit'])) {
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $phonePattern = '/^[0-9]{10}$/';
    if (!preg_match($phonePattern, $phone)) {
        $phoneErr = 'Неправильний формат телефонного номеру.';
    } else {
        $name = filter_input(INPUT_POST, 'clientName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sql = "INSERT INTO `product_requests`( `product_id`, `client_name`, `client_phone`, `comment`, `status`) 
                VALUES ('$product_id','$name','$phone','$comment','Новий')";
        if ($conn->query($sql)) {
            $sql = "SELECT id FROM `product_requests` ORDER BY `id` DESC LIMIT 1";
            $res = $conn->query($sql)->fetch_assoc();
            header("Location: inc/thanks.php?id=" . $res['id']);
            exit();
        }
    }

}

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sql = "SELECT * FROM product_list WHERE id = '$id' AND is_active = 1";
    $res = $conn->query($sql);
    if ($res->num_rows == 0) {
        $err = 'На жаль, не можна зробити заявку на неіснуючий продукт.';
        header("HTTP/1.1 404 Not Found");
        include("inc/error_page_404.php");
        exit();
    }
    $res = $res->fetch_assoc();
} else {
    header("Location: index.php");
    exit();
}
?>

<main>
    <a href="index.php?page=<?= $_GET['page'] ?>"><i class="bi bi-arrow-left-square-fill"></i></a>
    <div class="request-container">
        <div class="form-container">
            <form action="" method="post">
                <h3>Залишіть заявку на продукт '<?= $res['product_name'] ?>' за ціною <?= $res['price'] ?>
                    <span>₴</span> і
                    наш менеджер вам обов'язково зателефонує!</h3>
                <label>Введіть ваше ім'я:</label>
                <input type="text" name="clientName" required placeholder="Ім'я"
                       value="<?php if (isset($_POST['clientName'])) echo $_POST['clientName'] ?>">
                <label>Введіть ваш номер телефону:</label>
                <input type="tel" pattern="[0-9]{10}" name="phone" placeholder="Приклад: 0987654321" minlength="10"
                       size="10" maxlength="10" required
                       value="<?php if (isset($_POST['phone'])) echo $_POST['phone'] ?>">
                <?php if (isset($phoneErr)): ?>
                    <label class="error-message"><?= $phoneErr ?></label>
                <?php endif; ?>
                <label>Введіть коментар:</label>
                <input type="text" name="comment" required placeholder="Коментар"
                       value="<?php if (isset($_POST['comment'])) echo $_POST['comment'] ?>">
                <button type="submit" name="submit">Відправити</button>
            </form>
        </div>
    </div>
</main>

<?php
include 'inc/footer.php';
$conn->close();
?>
