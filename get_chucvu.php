<?php
include 'connect.php';

$sql = "SELECT id_chucvu, tenchucvu FROM chuc_vus";
$result = mysqli_query($conn, $sql);

$chucvus = [];
while ($row = mysqli_fetch_assoc($result)) {
    $chucvus[] = $row;
}

header('Content-Type: application/json');
echo json_encode($chucvus);
