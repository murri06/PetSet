<?php

// destroying session to logout user and redirecting to the homepage
session_start();
session_destroy();
header('Location: /petset/index.php');