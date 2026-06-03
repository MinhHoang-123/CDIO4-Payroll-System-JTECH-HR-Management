<?php
require 'connect.php';

$employees = [];
$subsidies = [];

$empQuery = "SELECT * FROM nhanvien";
$result = $conn->query($empQuery);
while ($row = $result->fetch_assoc()) {
  $empId = $row['id'];
  $subsidyQuery = "SELECT p.name, p.amount FROM nhanvienphucap np 
                   JOIN phucap p ON np.phucap_id = p.id 
                   WHERE np.nhanvien_id = $empId";
  $subsidyResult = $conn->query($subsidyQuery);
  $subs = [];
  while ($s = $subsidyResult->fetch_assoc()) {
    $subs[] = $s;
  }
  $employees[] = [
    "id" => $empId,
    "name" => $row['name'],
    "subsidies" => $subs
  ];
}

$subQuery = "SELECT * FROM phucap";
$res = $conn->query($subQuery);
while ($row = $res->fetch_assoc()) {
  $subsidies[] = $row;
}

echo json_encode([
  "employees" => $employees,
  "subsidies" => $subsidies
]);
$conn->close();
?>
