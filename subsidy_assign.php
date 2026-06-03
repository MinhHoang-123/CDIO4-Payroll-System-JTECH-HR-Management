<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"));

$nhanvien_id = $data->employee_id ?? 0;
$phucap_id = $data->subsidy_id ?? 0;

if ($nhanvien_id && $phucap_id) {
  $stmt = $conn->prepare("INSERT INTO nhanvienphucap (nhanvien_id, phucap_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $nhanvien_id, $phucap_id);
  if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Gán phụ cấp thành công."]);
  } else {
    echo json_encode(["success" => false, "message" => "Gán phụ cấp thất bại."]);
  }
  $stmt->close();
} else {
  echo json_encode(["success" => false, "message" => "Thiếu dữ liệu."]);
}
$conn->close();
?>
