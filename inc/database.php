<?php

// data used to connect to the database
const DB_USERNAME = 'petset_admin';
const DB_PASSWORD = 'rsVle[JyJRLex]BO';
const DB_HOST = 'localhost';
const DB_NAME = 'petset';

// connection to the database
$conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);


// if not, stopping the script
if ($conn->connect_errno) {
    echo $conn->connect_error;
    exit();
}

