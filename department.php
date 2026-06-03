<?php
session_start();
require_once 'connect.php';
require_once 'permission_functions.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Quyền
$can_view   = checkPermission($user_id, 'department.php', 'can_view', $conn);
$can_add    = checkPermission($user_id, 'department.php', 'can_add', $conn);
$can_delete = checkPermission($user_id, 'department.php', 'can_delete', $conn);

if (!$can_view) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
          <style>body{font-family:Arial;text-align:center;padding:50px;}h1{color:red;}</style>
          </head><body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p>
          <a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}

// Lấy danh sách phòng ban
$result = $conn->query("SELECT * FROM phong_bans ORDER BY id_phongban DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý phòng ban</title>
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
/* Main Content */
.main-content {
  flex: 1;
  padding: 20px;
  background: #1c1c1c; /* Nền tối */
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
   PHẦN 2: FORM, TABLE & BUTTONS
   ========================================= */

/* Input & Textarea */
input, textarea {
  width: 100%;
  padding: 10px;
  margin: 6px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Style chung cho Button */
button {
  padding: 10px 20px;
  margin-top: 10px;
  cursor: pointer;
  border-radius: 4px;
  font-weight: bold;
  transition: 0.3s;
  border: none;
}

/* 1. Nút Thêm (Add) -> Đổi từ Xanh lá sang Vàng (Ferrari Yellow) */
button.add-btn {
  background: #ffcc00; 
  color: #900000;
}

button.add-btn:hover {
  background: #ff0000;
  color: #fff;
}

/* 2. Nút Cập nhật (Update) -> Đổi từ Xanh dương sang Đỏ sẫm */
button.update-btn {
  background: #900000;
  color: #fff;
  border: 1px solid #ffcc00; /* Thêm viền vàng cho sang */
}

button.update-btn:hover {
  background: #ffcc00;
  color: #900000;
}

/* 3. Nút Xóa (Delete) -> Đổi từ Đỏ thường sang Đỏ tươi */
.btn-delete {
  background: #ff0000;
  color: white;
  border: none;
  padding: 6px 12px; /* Chỉnh lại padding cho đẹp hơn */
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

.btn-delete:hover {
  background: #b30000; /* Hover thì tối lại chút */
  transform: scale(1.05);
}

/* Table: Nền trắng chữ đen */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: #fff;
  color: #000;
}

th, td {
  border: 1px solid #ccc; /* Đổi màu border sang xám rõ hơn */
  padding: 10px;
  text-align: center;
}

th {
  background: #bdc3c7; /* Header xám sáng */
  color: #000;
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
      <a href="role.php">Chức Vụ</a>
      <a href="department.php"class="active">Phòng Ban</a>
      <a href="attendance.php">Chấm Công</a>
    </div>

    <div class="main-content">
        <h2>Danh sách phòng ban</h2>

        <?php if ($can_add): ?>
        <form id="addForm">
            <input type="hidden" name="id_phongban_edit" value="">
            <label>Tên phòng ban:</label>
            <input type="text" name="tenphongban" required>
            <label>Mô tả:</label>
            <textarea name="mota" rows="3"></textarea>
            <button type="submit" class="add-btn">Lưu phòng ban</button>
        </form>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên phòng ban</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_phongban'] ?></td>
                    <td><?= $row['tenphongban'] ?></td>
                    <td><?= $row['mota'] ?? '' ?></td>
                    <td>
                        <?php if ($can_add): ?>
                        <button class="update-btn" onclick="editDepartment(<?= $row['id_phongban'] ?>,'<?= addslashes($row['tenphongban']) ?>','<?= addslashes($row['mota']) ?>')">✎ Sửa</button>
                        <?php endif; ?>
                        <?php if ($can_delete): ?>
                        <button class="btn-delete" onclick="deleteDepartment(<?= $row['id_phongban'] ?>)">🗑 Xoá</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

<footer>&copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.</footer>

<script>
<?php if ($can_add): ?>
function editDepartment(id, name, mota) {
    const form = document.getElementById('addForm');
    form.id_phongban_edit.value = id;
    form.tenphongban.value = name;
    form.mota.value = mota;
    form.scrollIntoView({behavior:'smooth'});
}

document.getElementById('addForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    let action = data.id_phongban_edit ? 'edit' : 'add';
    if (data.id_phongban_edit === '') delete data.id_phongban_edit;

    fetch('department.api.php?action=' + action, {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message || "Thành công!");
        this.reset();
        location.reload();
    });
});
<?php endif; ?>

<?php if ($can_delete): ?>
function deleteDepartment(id) {
    if (!confirm('Xoá phòng ban này?')) return;

    fetch('department.api.php?action=delete', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id_phongban: id})
    })
    .then(res => res.json())
    .then(res => {
        alert(res.message || "Đã xóa!");
        location.reload();
    });
}
<?php endif; ?>
</script>

</body>
</html>
