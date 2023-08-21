<?php

include 'database.php';

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sql = "DELETE FROM product_list WHERE id = '$id'";
    $conn->query($sql);
}
header("Location: /petset/admin_panel.php?form=1");