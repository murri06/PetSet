<?php
include 'inc/database.php';
include 'inc/header.php';

// in case of form submitting, receiving and filtering information and prepairing it to creating a new db record
if (isset($_POST['submit'])) {
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $phonePattern = '/^[0-9]{10}$/';
    // phone validation and proceeding if succeed
    if (!preg_match($phonePattern, $phone)) {
        $phoneErr = 'Неправильний формат телефонного номеру.';
    } else {
        $name = filter_input(INPUT_POST, 'clientName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $product_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($_POST['comment']))
            $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        else
            $comment = '';
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


// receiving information about product from GET request
if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sql = "SELECT * FROM product_list WHERE id = '$id' AND is_active = 1";
    $res = $conn->query($sql);
    // if there is no existing product on sale with this id, showing 404 page
    if ($res->num_rows == 0) {
        $err = 'На жаль, не можна зробити заявку на неіснуючий продукт.';
        header("HTTP/1.1 404 Not Found");
        include("inc/error_page_404.php");
        exit();
    }
    $res = $res->fetch_assoc();
} else {
    // if there is no any of id, redirecting to homepage
    header("Location: index.php");
    exit();
}
?>

<main>
    <!-- button to get back to the product list   -->
    <a href="index.php?page=<?= $_GET['page'] ?? '1' ?>"><i class="bi bi-arrow-left-square-fill"></i></a>
    <div class="request-container">
        <div class="form-container">
            <!--  form for submitting request -->
            <form action="" method="post">
                <h3>Залишіть заявку на продукт '<?= $res['product_name'] ?>' за ціною <?= $res['price'] ?>
                    <span>₴</span> і
                    наш менеджер вам обов'язково зателефонує!</h3>
                <label>Введіть ваше ім'я:</label>
                <input type="text" name="clientName" required placeholder="Ім'я"
                       value="<?= $_POST['client_name'] ?? '' ?>">
                <label>Введіть ваш номер телефону:</label>
                <input type="tel" pattern="[0-9]{10}" name="phone" placeholder="Приклад: 0987654321" minlength="10"
                       size="10" maxlength="10" required
                       value="<?= $_POST['client_phone'] ?? '' ?>">
                <?php if (isset($phoneErr)): ?>
                    <label class="error-message"><?= $phoneErr ?></label>
                <?php endif; ?>
                <label>Введіть коментар (Необов'язково):</label>
                <input type="text" name="comment" placeholder="Коментар"
                       value="<?= $_POST['comment'] ?? '' ?>">
                <button type="submit" name="submit">Створити заявку</button>
            </form>
        </div>
    </div>
</main>

<?php
include 'inc/footer.php';
$conn->close();
?>
