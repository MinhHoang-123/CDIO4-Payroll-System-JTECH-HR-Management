<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header("Location: right.php");
  exit();
}

$id = (int)$_GET['id'];

// Lấy dữ liệu phân quyền theo ID
$stmt = $conn->prepare("SELECT * FROM permissions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Không tìm thấy phân quyền!";
  exit();
}

$permission = $result->fetch_assoc();

// Xử lý cập nhật khi submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $can_view = isset($_POST['view']) ? 1 : 0;
  $can_add = isset($_POST['add']) ? 1 : 0;
  $can_edit = isset($_POST['edit']) ? 1 : 0;
  $can_delete = isset($_POST['delete']) ? 1 : 0;

  $stmt = $conn->prepare("UPDATE permissions 
                          SET can_view = ?, can_add = ?, can_edit = ?, can_delete = ?
                          WHERE id = ?");
  $stmt->bind_param("iiiii", $can_view, $can_add, $can_edit, $can_delete, $id);
  $stmt->execute();

  echo "<script>alert('Cập nhật quyền thành công!'); window.location.href = 'right.php';</script>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa phân quyền</title>
  <style>
    body { font-family: Arial, sans-serif; background: #ecf0f1; padding: 30px; }
    .form-container { background: white; padding: 20px 30px; border-radius: 8px; max-width: 500px; margin: auto; }
    h2 { margin-bottom: 20px; }
    label { display: block; margin: 10px 0 5px; font-weight: bold; }
    input[type="checkbox"] { margin-right: 10px; }
    input[type="submit"] {
      margin-top: 20px;
      background-color: black;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    a.back {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #3498db;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Sửa quyền của người dùng ID <?= $permission['user_id'] ?> cho trang <b><?= $permission['page'] ?></b></h2>
    <form method="POST">
      <label><input type="checkbox" name="view" <?= $permission['can_view'] ? 'checked' : '' ?>> Quyền xem</label>
      <label><input type="checkbox" name="add" <?= $permission['can_add'] ? 'checked' : '' ?>> Quyền thêm</label>
      <label><input type="checkbox" name="edit" <?= $permission['can_edit'] ? 'checked' : '' ?>> Quyền sửa</label>
      <label><input type="checkbox" name="delete" <?= $permission['can_delete'] ? 'checked' : '' ?>> Quyền xoá</label>

      <input type="submit" value="Cập nhật">
    </form>
    <a class="back" href="right.php">&larr; Quay lại phân quyền</a>
  </div>
</body>
</html>
