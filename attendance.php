<?php
session_start();
require_once 'connect.php';
require_once 'permission_functions.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Lấy user hiện tại
$user_id = $_SESSION['user_id'] ?? 0;

// Quyền cho trang
$can_view   = checkPermission($user_id, 'attendance.php', 'can_view', $conn);
$can_add    = checkPermission($user_id, 'attendance.php', 'can_add', $conn);
$can_delete = checkPermission($user_id, 'attendance.php', 'can_delete', $conn);

// Không có quyền xem
if (!$can_view) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
          <style>body{font-family:Arial;text-align:center;padding:50px;}h1{color:red;}</style>
          </head><body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p>
          <a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}

// =====================
// Xử lý thêm/sửa chấm công
// =====================
if ($can_add && isset($_POST['save_attendance'])) {
    $id_chamcong = $_POST['id_chamcong'] ?? 0;
    $id_nhanvien = $_POST['id_nhanvien'];
    $ngaylamviec = $_POST['ngay'];
    $giovao = $_POST['giovao'] ?: '00:00:00';
    $giora = $_POST['giora'] ?: '00:00:00';
    $trangthai = $_POST['trangthai'];

    if ($id_chamcong > 0) {
        // Sửa
        $stmt = $conn->prepare("UPDATE cham_congs SET id_nhanvien=?, ngaylamviec=?, giovao=?, giora=?, trangthai=? WHERE id_chamcong=?");
        $stmt->bind_param("isssii", $id_nhanvien, $ngaylamviec, $giovao, $giora, $trangthai, $id_chamcong);
        $stmt->execute();
    } else {
        // Thêm mới
        $stmt = $conn->prepare("INSERT INTO cham_congs (id_nhanvien, ngaylamviec, giovao, giora, trangthai) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $id_nhanvien, $ngaylamviec, $giovao, $giora, $trangthai);
        $stmt->execute();
    }
}

// Xóa chấm công
if ($can_delete && isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM cham_congs WHERE id_chamcong=$id");
}

// =====================
// LẤY DỮ LIỆU & PHÂN TRANG
// =====================

// 1. Cấu hình phân trang
$limit = 10; // Số dòng trên 1 trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 2. Đếm tổng số dòng
$count_sql = "SELECT COUNT(*) as total FROM cham_congs";
$total_res = $conn->query($count_sql);
$total_records = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// 3. Lấy danh sách nhân viên cho Dropdown
$nhanvien_result = $conn->query("SELECT id_nhanvien, hoten FROM nhan_viens");

// 4. Lấy dữ liệu chấm công với LIMIT
$attendance_result = $conn->query("
    SELECT c.*, n.hoten 
    FROM cham_congs c 
    JOIN nhan_viens n ON c.id_nhanvien = n.id_nhanvien 
    ORDER BY ngaylamviec DESC 
    LIMIT $offset, $limit
");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý chấm công</title>
<style>
/* =========================================
   PHẦN 1: GIAO DIỆN CHÍNH (THEME FERRARI)
   ========================================= */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Arial', sans-serif;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: #1c1c1c; /* Nền đen bóng */
  color: #fff;
}

/* Header Gradient Đỏ */
.header {
  background: linear-gradient(90deg, #ff0000, #900000);
  color: #fff;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}

.header h1 {
  font-size: 28px;
  font-weight: bold;
  display: flex;
  align-items: center;
  color: #fff;
}

.header h1 i, .header h1 img {
  margin-right: 10px;
}

/* NÚT LOGOUT */
.header .logout-btn {
  background: #b30000;
  color: #fff;
  padding: 8px 16px;
  border: 1px solid #b30000;
  border-radius: 4px;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}

.header .logout-btn:hover {
  background: #ffcc00;
  color: #900000;
  border-color: #ffcc00;
  transform: scale(1.05);
}

.container { display: flex; flex: 1; }

/* Sidebar */
.sidebar {
  width: 220px;
  background: #900000;
  color: #fff;
  padding: 20px;
  border-right: 2px solid #ff0000;
}

.sidebar h2 {
   margin-bottom: 20px;
  color: #ffcc00;
  border-bottom: 1px solid rgba(255,255,255,0.2);
  padding-bottom: 10px;
}

.sidebar a {
  display: block;
  margin: 12px 0;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  padding: 8px;
  border-radius: 4px;
  transition: 0.3s;
}

.sidebar a:hover {
  background: #ff0000;
  padding-left: 15px;
}
.sidebar a.active {
  background: #ffcc00; /* Nền Vàng (giống header bảng) */
  color: #900000;      /* Chữ Đỏ */
  padding-left: 15px;  /* Thụt vào giống như đang hover */
  border-left: 5px solid #fff; /* Thêm cái vạch trắng bên trái cho nổi bật hẳn */
  box-shadow: 0 0 10px rgba(255, 204, 0, 0.5); /* Phát sáng nhẹ */
}
/* Main Content */
.main-content {
  flex: 1;
  padding: 20px;
  background: #1c1c1c;
  overflow: auto; 
}

/* Footer */
footer {
  background: #900000;
  color: #ffcc00;
  text-align: center;
  padding: 12px 0;
  font-weight: bold;
}

/* =========================================
   PHẦN 2: FORM & TABLE
   ========================================= */

h2.section-title {
  background: #ffcc00;
  color: #900000;
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 20px;
  font-weight: bold;
  border: 1px solid #ff0000;
}

/* Form nền trắng */
form {
  margin-bottom: 20px;
  background: #fff;
  color: #000;
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

/* Table nền trắng */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  color: #000;
  margin-bottom: 10px; /* Giảm margin để hiện phân trang đẹp hơn */
}

th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

th {
  background: #bdc3c7;
  color: #000;
}

/* Input, Select, Button */
input, select, button {
  padding: 8px 12px;
  margin: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  background: #ffcc00;
  color: #900000;
  border: none;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background: #ff0000;
  color: #fff;
}

.btn-delete {
  color: #ff0000;
  text-decoration: none;
  font-weight: bold;
}

.btn-delete:hover {
  text-decoration: underline;
  color: #b30000;
}

/* PHÂN TRANG */
.pagination { margin-top: 20px; margin-bottom: 30px; text-align: center; }
.pagination a {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 4px;
    background: #333;
    color: #fff;
    text-decoration: none;
    border: 1px solid #555;
    border-radius: 4px;
    font-weight: bold;
}
.pagination a:hover { background: #ff0000; }
.pagination a.active { background: #ffcc00; color: #900000; }
</style>
</head>
<body>

<div class="header">
  <h1>
  <img src="https://cdn-icons-png.freepik.com/512/6475/6475938.png" alt="icon" style="width:30px; vertical-align:middle; margin-right:10px;">
  Quản lý tiền lương
</h1>
  <a href="logout.php" class="logout-btn">Đăng xuất</a>
</div>

<div class="container">
    <div class="sidebar">
        <h2>Menu</h2>
       <a href="report.php">Báo Cáo</a>
       <a href="profile.php">Tài khoản của tôi</a>
       <a href="chatbot.php">CHATBOT AI</a>
       <a href="index.php">Trang chủ</a>
       <a href="employee.php">Nhân viên</a>
       <a href="pay.php">Tiền lương</a>
       <a href="right.php">Phân Quyền</a>
       <a href="subsidy.php">Phụ Cấp</a>
       <a href="role.php">Chức Vụ</a>
       <a href="department.php">Phòng Ban</a>
       <a href="attendance.php" class="active">Chấm Công</a>
    </div>

    <div class="main-content">

        <?php if($can_add): ?>
        <h2 class="section-title">Thêm/Sửa chấm công</h2>
        <form method="POST" id="form_attendance">
            <input type="hidden" name="id_chamcong" value="">
            <label>Nhân viên:</label>
            <select name="id_nhanvien" required>
                <?php while($nv=$nhanvien_result->fetch_assoc()): ?>
                    <option value="<?= $nv['id_nhanvien'] ?>"><?= $nv['hoten'] ?></option>
                <?php endwhile; ?>
            </select>
            <label>Ngày:</label>
            <input type="date" name="ngay" required>
            <label>Giờ vào:</label>
            <input type="time" name="giovao">
            <label>Giờ ra:</label>
            <input type="time" name="giora">
            <label>Trạng thái:</label>
            <select name="trangthai">
                <option value="1">Đi làm</option>
                <option value="0">Nghỉ phép</option>
            </select>
            <button type="submit" name="save_attendance">Lưu</button>
        </form>
        <?php endif; ?>

        <h2 class="section-title">Danh sách chấm công</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Tên NV</th><th>Ngày</th><th>Giờ vào</th><th>Giờ ra</th><th>Trạng thái</th><th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row=$attendance_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_chamcong'] ?></td>
                    <td><?= $row['hoten'] ?></td>
                    <td><?= $row['ngaylamviec'] ?></td>
                    <td><?= $row['giovao'] ?: '-' ?></td>
                    <td><?= $row['giora'] ?: '-' ?></td>
                    <td><?= $row['trangthai'] ? 'Đi làm' : 'Nghỉ phép' ?></td>
                    <td>
                        <?php if($can_add): ?>
                            <a href="#" onclick="editAttendance(<?= $row['id_chamcong'] ?>,<?= $row['id_nhanvien'] ?>,'<?= $row['ngaylamviec'] ?>','<?= $row['giovao'] ?>','<?= $row['giora'] ?>',<?= $row['trangthai'] ?>)">Sửa</a>
                        <?php endif; ?>
                        <?php if($can_delete): ?>
                            | <a class="btn-delete" href="?delete=<?= $row['id_chamcong'] ?>" onclick="return confirm('Xoá chấm công này?')">Xoá</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1">&laquo; Đầu</a>
                <a href="?page=<?= $page - 1 ?>">&lsaquo; Trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>">Sau &rsaquo;</a>
                <a href="?page=<?= $total_pages ?>">Cuối &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        </div>
</div>

<footer>
&copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.
</footer>

<script>
function editAttendance(id, id_nv, ngay, giovao, giora, trangthai){
    const form = document.getElementById('form_attendance');
    form.id_chamcong.value = id;
    form.id_nhanvien.value = id_nv;
    form.ngay.value = ngay;
    form.giovao.value = giovao === '00:00:00' ? '' : giovao;
    form.giora.value = giora === '00:00:00' ? '' : giora;
    form.trangthai.value = trangthai;
    form.scrollIntoView({behavior:'smooth'});
}
</script>

</body>
</html>