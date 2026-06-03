<?php
require 'connect.php';

$data = json_decode(file_get_contents("php://input"));
$nhanvien_id = $data->employee_id ?? 0;
$phucap_name = $data->subsidy_name ?? '';

if ($nhanvien_id && $phucap_name) {
  $stmt = $conn->prepare("SELECT id FROM phucap WHERE name = ?");
  $stmt->bind_param("s", $phucap_name);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $phucap_id = $row['id'];
    $conn->query("DELETE FROM nhanvienphucap WHERE nhanvien_id = $nhanvien_id AND phucap_id = $phucap_id");
    echo json_encode(["success" => true, "message" => "Xoá phụ cấp khỏi nhân viên thành công."]);
  } else {
    echo json_encode(["success" => false, "message" => "Không tìm thấy phụ cấp."]);
  }
  $stmt->close();
} else {
  echo json_encode(["success" => false, "message" => "Thiếu dữ liệu."]);
}
$conn->close();
?>
