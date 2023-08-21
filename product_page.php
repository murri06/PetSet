<?php
include 'inc/database.php';
include 'inc/header.php';


if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sql = "SELECT * FROM product_list WHERE id = '$id' AND is_active = 1";
    $res = $conn->query($sql);
    if ($res->num_rows == 0) {
        $err = 'На жаль, такого продукту не існує.';
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
    <div class="detail-container">
        <a href="index.php?page=<?= $_GET['page'] ?>"><i class="bi bi-arrow-left-square-fill"></i></a>
        <div class="product-detail-container">
            <div class="photo-wrapper">
                <img src="photo/<?= $res['photo'] ?>" alt="photo of product" width="512" height="512">
            </div>
            <div class="info-details">
                <div class="name-price">
                    <h1><?= $res['product_name'] ?></h1>
                    <h3 class="article">Артикул:<?= $res['article'] ?></h3>
                    <div class="detail-price">
                        <h2><?= $res['price'] ?> <span>₴</span></h2>
                        <a href="product_request.php?id=<?= $res['id'] ?><?php if (isset($page)) echo "&page=" . $page + 1; else echo "&page=1" ?>">
                            <button>Залишити заявку</button>
                        </a>
                    </div>
                </div>
                <h3><?= $res['description'] ?></h3>
            </div>
        </div>
    </div>
</main>

<?php
include 'inc/footer.php';
?>
