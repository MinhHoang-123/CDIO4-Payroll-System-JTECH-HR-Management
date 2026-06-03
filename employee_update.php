<?php
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['id_nhanvien'], $data['hoten'], $data['gioitinh'], $data['ngaysinh'], $data['quequan'],
          $data['email'], $data['sdt'], $data['diachi'], $data['ngayvaolam'], $data['id_phongban'], $data['id_chucvu'])
) {
    $id = $data['id_nhanvien'];
    $hoten = $data['hoten'];
    $gioitinh = $data['gioitinh'];
    $ngaysinh = $data['ngaysinh'];
    $quequan = $data['quequan'];
    $email = $data['email'];
    $sdt = $data['sdt'];
    $diachi = $data['diachi'];
    $ngayvaolam = $data['ngayvaolam'];
    $id_phongban = $data['id_phongban'];
    $id_chucvu = $data['id_chucvu'];

    $query = "UPDATE nhan_viens 
              SET hoten = ?, gioitinh = ?, ngaysinh = ?, quequan = ?, email = ?, sdt = ?, diachi = ?, ngayvaolam = ?, id_phongban = ?, id_chucvu = ?
              WHERE id_nhanvien = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssiii", $hoten, $gioitinh, $ngaysinh, $quequan, $email, $sdt, $diachi, $ngayvaolam, $id_phongban, $id_chucvu, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu cần thiết"]);
}
?>
