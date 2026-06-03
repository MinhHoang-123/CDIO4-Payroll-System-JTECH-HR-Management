<?php
session_start();
require_once 'connect.php';
require_once 'permission_functions.php';

// Lấy user hiện tại
$user_id = $_SESSION['user_id'] ?? 0;

// Kiểm tra quyền
$can_view   = checkPermission($user_id, 'role.php', 'can_view', $conn);
$can_add    = checkPermission($user_id, 'role.php', 'can_add', $conn);
$can_delete = checkPermission($user_id, 'role.php', 'can_delete', $conn);

if (!$can_view) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
          <style>body{font-family:Arial;text-align:center;padding:50px;}h1{color:red;}</style>
          </head><body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p>
          <a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý chức vụ</title>
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

/* --- NÚT LOGOUT (ĐÚNG YÊU CẦU CỦA BẠN) --- */
.header .logout-btn {
  background: #b30000;
  color: #fff;
  border: 1px solid #b30000;
  padding: 8px 16px;
  cursor: pointer;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}

/* Hiệu ứng: Nền Vàng - Chữ Đỏ - Phóng to */
.header .logout-btn:hover {
  background: #ffcc00;
  color: #900000;
  border-color: #ffcc00;
  transform: scale(1.05);
}
/* ----------------------------------------- */

.container { display: flex; flex: 1; }

/* Sidebar Đỏ sẫm */
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
/* Main Content Nền Tối */
.main-content {
  flex: 1;
  padding: 20px;
  background: #1c1c1c;
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
   PHẦN 2: TABLE, FORM & BUTTONS
   ========================================= */

/* Tiêu đề Section: Đổi từ Xanh dương sang Vàng Ferrari */
h2.section-title {
  background: #ffcc00; /* Vàng */
  color: #900000;      /* Chữ Đỏ */
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 20px;
  border: 1px solid #ff0000;
  font-weight: bold;
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
  background: white;
  margin-top: 20px;
  color: #000;
}

th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

th {
  background: #bdc3c7; /* Header bảng màu xám */
  color: #000;
}

input, button {
  padding: 8px 12px;
  margin: 5px;
}

/* Các nút chức năng (Edit/Delete) */
.btn-edit {
  background-color: #ffcc00; /* Vàng tươi */
  color: #900000;            /* Chữ đỏ */
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  text-decoration: none;
  font-weight: bold;
  border: 1px solid #e1b700;
  display: inline-block; /* Để padding hoạt động tốt trên thẻ a */
}

.btn-delete {
  background-color: #ff0000; /* Đỏ tươi */
  color: white;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  text-decoration: none;
  font-weight: bold;
  border: 1px solid #cc0000;
  display: inline-block;
}

.btn-edit:hover, .btn-delete:hover {
  opacity: 0.8;
}
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
      <a href="role.php"class="active">Chức Vụ</a>
      <a href="department.php">Phòng Ban</a>
      <a href="attendance.php">Chấm Công</a>
    </div>

    <div class="main-content">
        <h2 class="section-title">Danh sách chức vụ</h2>

        <?php if ($can_add): ?>
        <form id="roleForm">
            <input type="hidden" name="id_chucvu" id="id_chucvu">
            <input type="text" name="tenchucvu" id="tenchucvu" placeholder="Tên chức vụ" required>
            <input type="number" name="hesoluong" id="hesoluong" placeholder="Hệ số lương" step="0.01" required>
            <button type="submit">Lưu</button>
        </form>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên chức vụ</th>
                    <th>Hệ số lương</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="roleList">
                <!-- Danh sách sẽ load bằng JS -->
            </tbody>
        </table>
    </div>
</div>

<footer>&copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.</footer>

<script>
const can_add = <?= $can_add ? 'true' : 'false' ?>;
const can_delete = <?= $can_delete ? 'true' : 'false' ?>;

// Load danh sách chức vụ
async function loadRoles() {
    const res = await fetch('role.api.php');
    const data = await res.json();
    const tbody = document.getElementById('roleList');
    tbody.innerHTML = '';
    data.data.forEach(role => {
        tbody.innerHTML += `<tr>
            <td>${role.id_chucvu}</td>
            <td>${role.tenchucvu}</td>
            <td>${role.hesoluong}</td>
            <td>
                ${can_add ? `<button class="btn-edit" onclick='editRole(${JSON.stringify(role)})'>Sửa</button>` : ''}
                ${can_delete ? `<button class="btn-delete" onclick='deleteRole(${role.id_chucvu})'>Xoá</button>` : ''}
            </td>
        </tr>`;
    });
}

// Thêm / Sửa
document.getElementById('roleForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('id_chucvu').value;
    const ten = document.getElementById('tenchucvu').value;
    const heSo = parseFloat(document.getElementById('hesoluong').value);

    const method = id ? 'PUT' : 'POST';
    const payload = id ? {id_chucvu: parseInt(id), tenchucvu: ten, hesoluong: heSo} : {tenchucvu: ten, hesoluong: heSo};

    const res = await fetch('role.api.php', {
        method: method,
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    });
    const data = await res.json();
    alert(data.message);
    document.getElementById('id_chucvu').value = '';
    document.getElementById('tenchucvu').value = '';
    document.getElementById('hesoluong').value = '';
    loadRoles();
});

// Sửa
function editRole(role) {
    document.getElementById('id_chucvu').value = role.id_chucvu;
    document.getElementById('tenchucvu').value = role.tenchucvu;
    document.getElementById('hesoluong').value = role.hesoluong;
}

// Xoá
async function deleteRole(id) {
    if (!confirm('Xoá chức vụ này?')) return;
    const res = await fetch('role.api.php', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id_chucvu: id})
    });
    const data = await res.json();
    alert(data.message);
    loadRoles();
}

// Load lần đầu
loadRoles();
</script>
</body>
</html>
