<?php
session_start();
session_destroy();
header('Location: /petset/index.php');