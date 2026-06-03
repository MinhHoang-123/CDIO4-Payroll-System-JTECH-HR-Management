<?php
include 'connect.php';

$sql = "SELECT id_phongban, tenphongban FROM phong_bans";
$result = mysqli_query($conn, $sql);

$phongbans = [];
while ($row = mysqli_fetch_assoc($result)) {
    $phongbans[] = $row;
}

header('Content-Type: application/json');
echo json_encode($phongbans);
