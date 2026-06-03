<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "cdio_db";



$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
