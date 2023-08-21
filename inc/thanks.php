<?php

include 'header.php';

if (isset($_GET['id'])) {
    $request_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
} else {
    header("Location: /petset/admin_panel.php");
    exit();
}
?>

<main>
    <div class="container">
        <h2>Дякуємо за заявку. Заявка номер <?= $request_id ?>, очікуйте, протягом
            доби з вам зв’яжеться менеджер.</h2>
        <a href="/petset/index.php" class="home-link">На головну</a>
    </div>
    <?php include 'footer.php' ?>

</main>
