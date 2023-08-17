<?php

include 'database.php';
session_start();

if (isset($_POST['login'])) {

    $login = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $res = $conn->query("SELECT * FROM user_list WHERE `username` = '$login' LIMIT 1");
    if ($res->num_rows > 0) {

        $res = $res->fetch_assoc();
        if (password_verify($password, $res['password'])) {
            $_SESSION['username'] = $res['username'];
            $_SESSION['userId'] = $res['id'];
            header("Location: /petset/index.php");

        } else header("Location: /petset/index.php?e=4");

    } else header("Location:/petset/index.php?e=4");
}

if (isset($_POST['register'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password2 = filter_input(INPUT_POST, 'passwordRepeat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $res = $conn->query("SELECT * FROM user_list WHERE `username` = '$username' LIMIT 1");
    if ($res->num_rows > 0) {
        header("Location: /petset/index.php?e=0");
        exit();
    }

    if ($password !== $password2) {
        header("Location: /petset/index.php?e=1");
        exit();
    }

    $password = password_hash($password, PASSWORD_BCRYPT);
    if ($conn->query("INSERT INTO user_list(`username`, `password`) VALUES ('$username', '$password')")) {
        header("Location: /petset/index.php?e=2");
        exit();
    } else
        header("Location: /petset/index.php?e=3");
}