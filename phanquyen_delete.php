<?php
include 'connect.php';

$id = $_POST['id_phanquyen'];
$conn->query("DELETE FROM phanquyen WHERE id_phanquyen = $id");

header("Location: right.php");
exit;
