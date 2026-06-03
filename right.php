<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Xử lý phân quyền khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_POST['user_id'];
  $pages = $_POST['page'];

  $stmt = $conn->prepare("DELETE FROM permissions WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();

  foreach ($pages as $page => $rights) {
    $can_view = isset($rights['view']) ? 1 : 0;
    $can_add = isset($rights['add']) ? 1 : 0;
    $can_edit = isset($rights['edit']) ? 1 : 0;
    $can_delete = isset($rights['delete']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO permissions (user_id, page, can_view, can_add, can_edit, can_delete) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiii", $user_id, $page, $can_view, $can_add, $can_edit, $can_delete);
    $stmt->execute();
  }

  echo "<script>alert('Phân quyền thành công!');</script>";
}

// Lấy danh sách user và các trang cần phân quyền
$users = $conn->query("SELECT id, name FROM users");
$pages = ['employee.php', 'pay.php', 'subsidy.php', 'role.php', 'department.php', 'attendance.php', 'chatbot.php', 'report.php'];

// --- PHẦN MỚI THÊM: CẤU HÌNH PHÂN TRANG ---
$limit = 10; // Số dòng trên 1 trang
$curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($curr_page < 1) $curr_page = 1;
$offset = ($curr_page - 1) * $limit;
// ------------------------------------------
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Phân quyền người dùng</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* =========================================
   PHẦN 1: GIAO DIỆN CHÍNH (THEME FERRARI)
   (Thay thế Body, Header, Sidebar, Footer)
   ========================================= */

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Arial', sans-serif;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: #1c1c1c; /* Nền đen bóng */
  color: #fff;
}

/* Header mới */
.header {
  background: linear-gradient(90deg, #ff0000, #900000); /* Đỏ Ferrari */
  color: #fff;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}

/* --- NÚT LOGOUT (ĐÃ SỬA LẠI ĐÚNG CHUẨN) --- */
.header .logout-btn {
  background: #b30000; /* Nền đỏ sẫm */
  color: #fff;         /* Chữ Trắng (Giống gốc) */
  border: 1px solid #b30000; /* Viền đỏ */
  padding: 8px 16px;
  cursor: pointer;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}

/* Hiệu ứng Hover: Nền Vàng - Chữ Đỏ - Phóng to */
.header .logout-btn:hover {
  background: #ffcc00;
  color: #900000;
  border-color: #ffcc00;
  transform: scale(1.05);
}
/* ------------------------------------------- */

.container {
  display: flex;
  flex: 1;
}

/* Sidebar mới */
.sidebar {
  width: 220px;
  padding: 20px;
  background: #900000; /* Đỏ sẫm */
  border-right: 2px solid #ff0000;
  color: #fff;
}

.sidebar h2 {
  margin-bottom: 20px;
  color: #ffcc00;
  border-bottom: 1px solid rgba(255,255,255,0.2);
  padding-bottom: 10px;
}

.sidebar a {
  display: block;
  color: #fff;
  text-decoration: none;
  margin: 12px 0;
  font-weight: bold;
  padding: 8px;
  border-radius: 4px;
  transition: 0.3s;
}

.sidebar a:hover {
  background: #ff0000;
  text-decoration: none;
  padding-left: 15px;
}
/* --- THÊM ĐOẠN NÀY --- */
.sidebar a.active {
  background: #ffcc00; /* Nền Vàng (giống header bảng) */
  color: #900000;      /* Chữ Đỏ */
  padding-left: 15px;  /* Thụt vào giống như đang hover */
  border-left: 5px solid #fff; /* Thêm cái vạch trắng bên trái cho nổi bật hẳn */
  box-shadow: 0 0 10px rgba(255, 204, 0, 0.5); /* Phát sáng nhẹ */
}
/* Main Content mới */
.main-content {
  flex: 1;
  padding: 20px;
  background: #1c1c1c; /* Nền tối đồng bộ */
}

/* Footer mới */
footer {
  background: #900000;
  color: #ffcc00;
  text-align: center;
  padding: 12px 0;
  font-weight: bold;
}

/* =========================================
   PHẦN 2: FORM & TABLE (GIỮ TÍNH NĂNG CŨ)
   ========================================= */

/* Bảng: Ép nền trắng để dễ đọc chữ đen */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff; 
  color: #000;
  margin-top: 10px;
}

th, td {
  border: 1px solid #000;
  padding: 8px 10px;
}

th {
  background-color: #bdc3c7;
  color: #000;
}

/* Label */
label {
  font-weight: bold;
  display: block;
  margin: 15px 0 5px;
  color: #ffcc00; /* Vàng Ferrari */
}

/* Input & Select */
select, input[type="text"], input[type="number"] {
  padding: 10px;
  width: 100%;
  max-width: 300px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Nút Submit */
input[type="submit"] {
  padding: 10px 20px;
  width: 100%;
  max-width: 300px;
  margin-top: 20px;
  cursor: pointer;
  background: #ffcc00; /* Nền Vàng */
  color: #900000;      /* Chữ Đỏ */
  border: none;
  font-weight: bold;
  text-transform: uppercase;
  border-radius: 4px;
  transition: 0.3s;
}

input[type="submit"]:hover {
  background: #ff0000;
  color: #fff;
}

/* CSS PHÂN TRANG (MỚI THÊM) */
.pagination { margin-top: 20px; text-align: center; }
.pagination a {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 4px;
    background: #333;
    color: #fff;
    text-decoration: none;
    border: 1px solid #555;
    border-radius: 4px;
}
.pagination a:hover { background: #ff0000; }
.pagination a.active { background: #ffcc00; color: #900000; font-weight: bold; }
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
      <a href="right.php"class="active">Phân Quyền</a>
      <a href="subsidy.php">Phụ Cấp</a>
      <a href="role.php">Chức Vụ</a>
      <a href="department.php">Phòng Ban</a>
      <a href="attendance.php">Chấm Công</a>
    </div>

    <div class="main-content">
      <h2>Phân quyền cho người dùng</h2>
      <form method="POST">
        <label for="user_id">Chọn người dùng:</label>
        <select name="user_id" required>
          <option value="">-- Chọn --</option>
          <?php while($row = $users->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>

        <table style="margin-top: 20px;">
          <tr>
            <th>Trang</th>
            <th>Xem</th>
            <th>Thêm</th>
            <th>Sửa</th>
            <th>Xoá</th>
          </tr>
          <?php foreach ($pages as $page): ?>
            <tr>
              <td><?= $page ?></td>
              <td><input type="checkbox" name="page[<?= $page ?>][view]"></td>
              <td><input type="checkbox" name="page[<?= $page ?>][add]"></td>
              <td><input type="checkbox" name="page[<?= $page ?>][edit]"></td>
              <td><input type="checkbox" name="page[<?= $page ?>][delete]"></td>
            </tr>
          <?php endforeach; ?>
        </table>

        <input type="submit" value="Lưu phân quyền">
      </form>
      <hr style="margin: 30px 0;">

<h2>Danh sách phân quyền</h2>
<table>
  <tr>
    <th>Người dùng</th>
    <th>Trang</th>
    <th>Xem</th>
    <th>Thêm</th>
    <th>Sửa</th>
    <th>Xoá</th>
    <th>Hành động</th>
  </tr>
  <?php
  // --- SỬA LOGIC TRUY VẤN ĐỂ PHÂN TRANG ---
  
  // 1. Đếm tổng số dòng
  $count_sql = "SELECT COUNT(*) as total FROM permissions";
  $total_res = $conn->query($count_sql);
  $total_records = $total_res->fetch_assoc()['total'];
  $total_pages = ceil($total_records / $limit);

  // 2. Lấy dữ liệu với LIMIT
  $sql = "SELECT p.*, u.name 
          FROM permissions p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY u.name, p.page
          LIMIT $offset, $limit";
  // ----------------------------------------
  
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()):
  ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= $row['page'] ?></td>
      <td><?= $row['can_view'] ? '✔️' : '❌' ?></td>
      <td><?= $row['can_add'] ? '✔️' : '❌' ?></td>
      <td><?= $row['can_edit'] ? '✔️' : '❌' ?></td>
      <td><?= $row['can_delete'] ? '✔️' : '❌' ?></td>
      <td>
        <a href="edit_permission.php?id=<?= $row['id'] ?>">Sửa</a> |
        <a href="delete_permission.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xoá quyền này?')">Xoá</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<?php if ($total_pages > 1): ?>
<div class="pagination">
    <?php if ($curr_page > 1): ?>
        <a href="?page=1">&laquo; Đầu</a>
        <a href="?page=<?= $curr_page - 1 ?>">&lsaquo; Trước</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" class="<?= ($i == $curr_page) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($curr_page < $total_pages): ?>
        <a href="?page=<?= $curr_page + 1 ?>">Sau &rsaquo;</a>
        <a href="?page=<?= $total_pages ?>">Cuối &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>
</div>
  </div>

  <footer>
    &copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.
  </footer>
</body>
</html>