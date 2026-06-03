<?php
include 'connect.php';

$tenquyen = $_POST['tenquyen'];
$mota = $_POST['mota'];

$stmt = $conn->prepare("INSERT INTO quyen (tenquyen, mota, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
$stmt->bind_param("ss", $tenquyen, $mota);
$stmt->execute();

header("Location: right.php");
exit;
