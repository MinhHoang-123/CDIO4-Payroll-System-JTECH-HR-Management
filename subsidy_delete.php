<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"));
$id = $data->id ?? 0;

if ($id) {
  $conn->query("DELETE FROM nhanvienphucap WHERE phucap_id = $id");
  $conn->query("DELETE FROM phucap WHERE id = $id");
  echo json_encode(["success" => true, "message" => "Xoá phụ cấp thành công."]);
} else {
  echo json_encode(["success" => false, "message" => "Không tìm thấy ID cần xoá."]);
}
$conn->close();
?>
