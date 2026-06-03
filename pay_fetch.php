<?php
require 'connect.php';

$result = $conn->query("SELECT * FROM luong");
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
