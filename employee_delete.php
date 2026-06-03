<?php
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_nhanvien'])) {
    $id = $data['id_nhanvien'];
    $query = "UPDATE nhan_viens SET trangthai = 0 WHERE id_nhanvien = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu ID nhân viên"]);
}
?>
