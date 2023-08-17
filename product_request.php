<?php
include 'inc/database.php';
include 'inc/header.php';


if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sql = "SELECT * FROM product_list WHERE id = '$id' AND is_active = 1";
    $res = $conn->query($sql);
    if ($res->num_rows == 0) {
        $err = 'На жаль, не можна зробити заявку на неіснуючий продукт.';
        header("HTTP/1.1 404 Not Found");
        include("inc/404.php");
        exit();
    }
    $res = $res->fetch_assoc();
}
?>

<main>
    <div class="form-container">
        <form action="" method="post">
            <h3>Залишіть заявку на продукт '<?=$res['product_name'] ?>' за ціною <?=$res['price'] ?> <span>₴</span> і наш менеджер вам обов'язково перетелефонує!</h3>

        </form>
    </div>
</main>

<?php
include 'inc/footer.php';
?>
