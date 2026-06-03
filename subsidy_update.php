<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

$id = $data->id ?? 0;
$amount = $data->amount ?? 0;

if ($id && $amount > 0) {
  $stmt = $conn->prepare("UPDATE phucap SET amount = ? WHERE id = ?");
  $stmt->bind_param("ii", $amount, $id);
  if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Cập nhật thành công."]);
  } else {
    echo json_encode(["success" => false, "message" => "Lỗi cập nhật."]);
  }
  $stmt->close();
} else {
  echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ."]);
}
$conn->close();
?>
