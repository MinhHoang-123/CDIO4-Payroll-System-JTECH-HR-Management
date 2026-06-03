<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)$data['id'];
$field = $conn->real_escape_string($data['field']);
$value = (int)$data['value'];

$allowed = ['luong_co_ban', 'thuong', 'phat'];
if (in_array($field, $allowed)) {
  $sql = "UPDATE luong SET $field = $value WHERE id = $id";
  $conn->query($sql);
}

$conn->close();
?>
