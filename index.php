<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang chủ</title>
<style>
/* Reset mặc định */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Body nền đen */
body {
  font-family: 'Arial', sans-serif;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: #1c1c1c; /* nền đen bóng */
  color: #fff;
}

/* Header */
.header {
  background: linear-gradient(90deg, #ff0000, #900000); /* đỏ Ferrari */
  color: #fff;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.5);
  border-radius: 0 0 8px 8px;
}

.header h1 {
  font-size: 28px;
  font-weight: bold;
  display: flex;
  align-items: center;
  color: #fff;
}

.header h1 i {
  margin-right: 10px;
  color: #ffcc00; /* icon vàng nổi bật */
}

/* --- NÚT ĐĂNG XUẤT (ĐÃ SỬA) --- */
.header .logout-btn {
  background: #ff0000;
  border: 1px solid #ff0000; /* Thêm viền để khi hover đổi màu mượt hơn */
  padding: 10px 16px;
  cursor: pointer;
  border-radius: 6px;
  color: #fff;
  font-weight: bold;
  transition: 0.3s;
  text-decoration: none; /* Đề phòng thẻ a */
}

/* Hiệu ứng Hover mới: Nền Vàng - Chữ Đỏ */
.header .logout-btn:hover {
  background: #ffcc00;
  color: #900000;
  border-color: #ffcc00;
  transform: scale(1.05);
}
/* -------------------------------- */

/* Container */
.container {
  display: flex;
  flex: 1;
}

/* Sidebar */
.sidebar {
  width: 220px;
  padding: 20px;
  background: #900000;
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
  color: #fff;
  text-decoration: none;
  margin: 12px 0;
  font-weight: bold;
  padding: 6px 12px;
  border-radius: 6px;
  transition: 0.3s;
}

.sidebar a:hover {
  background: #ff0000;
  color: #fff;
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

/* Chatbox Styles */
#chatBox {
  background: #2c2c2c;
  border: 1px solid #ff0000;
  padding: 10px;
  border-radius: 6px;
  height: 400px;
  overflow-y: auto;
}

.message.user {
  color: #ffcc00; /* người dùng vàng */
  margin: 5px 0;
}

.message.bot {
  color: #ff0000; /* bot đỏ Ferrari */
  margin: 5px 0;
}

.suggest-btn {
  margin: 2px;
  padding: 4px 8px;
  background: #ffcc00; /* vàng nổi bật */
  color: #900000;       /* chữ đỏ sẫm */
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

.suggest-btn:hover {
  background: #ff0000;
  color: #fff;
}

/* Footer */
footer {
  background: #900000;
  color: #ffcc00;
  text-align: center;
  padding: 12px 0;
  font-weight: bold;
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
      <a href="index.php"class="active">Trang chủ</a>
      <a href="employee.php">Nhân viên</a>
      <a href="pay.php">Tiền lương</a>
      <a href="right.php">Phân Quyền</a>
      <a href="subsidy.php">Phụ Cấp</a>
      <a href="role.php">Chức Vụ</a>
      <a href="department.php">Phòng Ban</a>
      <a href="attendance.php">Chấm Công</a>
    </div>

    <div class="main-content">
      <h2>Chào mừng bạn!</h2>
      <p>Chọn một chức năng từ menu bên trái để bắt đầu quản lý.</p>
    </div>
  </div>

  <footer>
    &copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.
  </footer>
</body>
</html>
