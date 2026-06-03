<?php
include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['hoten'], $data['gioitinh'], $data['ngaysinh'], $data['quequan'], $data['email'],
          $data['sdt'], $data['diachi'], $data['ngayvaolam'], $data['id_phongban'], $data['id_chucvu'])
) {
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

    $query = "INSERT INTO nhan_viens (hoten, gioitinh, ngaysinh, quequan, email, sdt, diachi, ngayvaolam, id_phongban, id_chucvu, trangthai, matkhau)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, '1234')";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssii", $hoten, $gioitinh, $ngaysinh, $quequan, $email, $sdt, $diachi, $ngayvaolam, $id_phongban, $id_chucvu);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu đầu vào"]);
}
?>
