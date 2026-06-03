<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header("Location: right.php");
  exit();
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("DELETE FROM permissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: right.php");
exit();
