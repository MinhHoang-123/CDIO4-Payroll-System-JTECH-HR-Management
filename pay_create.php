<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$ten = $conn->real_escape_string($data['ten']);
$luong = (int)$data['luong_co_ban'];

$sql = "INSERT INTO luong (ten, luong_co_ban, thuong, phat) VALUES ('$ten', $luong, 0, 0)";
$conn->query($sql);
$conn->close();
?>
