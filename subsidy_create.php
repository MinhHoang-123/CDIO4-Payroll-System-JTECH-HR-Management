<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

$name = $data->name ?? '';
$amount = $data->amount ?? 0;

if ($name && $amount > 0) {
  $stmt = $conn->prepare("INSERT INTO phucap (name, amount) VALUES (?, ?)");
  $stmt->bind_param("si", $name, $amount);
  if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Thêm phụ cấp thành công."]);
  } else {
    echo json_encode(["success" => false, "message" => "Lỗi khi thêm phụ cấp."]);
  }
  $stmt->close();
} else {
  echo json_encode(["success" => false, "message" => "Thiếu dữ liệu."]);
}
$conn->close();
?>
