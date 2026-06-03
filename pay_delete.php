<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)$data['id'];

$sql = "DELETE FROM luong WHERE id = $id";
$conn->query($sql);
$conn->close();
?>
