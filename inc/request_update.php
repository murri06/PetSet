<?php
include 'database.php';

if (isset($_POST['submit'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_POST['comment']))
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    else $comment = '';

    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $sql = "UPDATE `product_requests` SET `admin_comment`='$comment',`status`='$status' WHERE id = '$id'";
    $conn->query($sql);
}
header("Location: /petset/admin_panel.php?form=2");