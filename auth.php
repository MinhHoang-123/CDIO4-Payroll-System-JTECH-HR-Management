<?php
session_start();

// Kiểm tra nếu chưa đăng nhập thì chuyển về trang login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Danh sách các file cho phép user thường truy cập (chỉ xem)
$allowed_view_only = [
    'employee.php',
    'pay.php',
    'subsidy.php',
    'role.php',
    'department.php',
    'attendance.php'
];

// Lấy tên file hiện tại đang truy cập
$current_file = basename($_SERVER['PHP_SELF']);

// Kiểm tra người dùng có phải là admin không
$is_admin = ($_SESSION['username'] === 'admin');

// Nếu không phải admin và truy cập file không được phép
if (!$is_admin && !in_array($current_file, $allowed_view_only)) {
    echo "<h2 style='color:red; text-align:center;'>Bạn không có quyền truy cập trang này!</h2>";
    exit;
}

// Định nghĩa hằng số để dùng trong các trang HTML (ẩn nút thêm/sửa/xoá)
define('IS_ADMIN', $is_admin);
