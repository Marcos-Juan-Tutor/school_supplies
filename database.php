<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "school_supplies";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
