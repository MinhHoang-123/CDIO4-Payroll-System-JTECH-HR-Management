<?php
include 'connect.php';

$query = "SELECT nv.*, pb.tenphongban, cv.tenchucvu 
          FROM nhan_viens nv
          JOIN phong_bans pb ON nv.id_phongban = pb.id_phongban
          JOIN chuc_vus cv ON nv.id_chucvu = cv.id_chucvu
          WHERE nv.trangthai = 1";

$result = $conn->query($query);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
