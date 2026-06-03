<?php
include 'connect.php';

$id_nhanvien = $_POST['id_nhanvien'];
$id_quyen = $_POST['id_quyen'];

// Tránh gán trùng
$stmt = $conn->prepare("SELECT * FROM phanquyen WHERE id_nhanvien=? AND id_quyen=?");
$stmt->bind_param("ii", $id_nhanvien, $id_quyen);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  $stmt = $conn->prepare("INSERT INTO phanquyen (id_nhanvien, id_quyen, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
  $stmt->bind_param("ii", $id_nhanvien, $id_quyen);
  $stmt->execute();
}

header("Location: right.php");
exit;
