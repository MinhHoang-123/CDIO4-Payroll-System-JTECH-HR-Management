<?php
session_start();
include 'connect.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";
$error = "";

// 2. LẤY THÔNG TIN HIỆN TẠI TRƯỚC (Để lấy mật khẩu cũ so sánh)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 3. XỬ LÝ CẬP NHẬT THÔNG TIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $new_password = $_POST['new_password']; // Mật khẩu mới
    $current_password = $_POST['current_password']; // Mật khẩu cũ

    // Logic xử lý
    $allow_update = true;
    $update_pass = false;

    // Nếu người dùng muốn đổi mật khẩu (ô mật khẩu mới không để trống)
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $error = "Vui lòng nhập mật khẩu hiện tại để xác nhận thay đổi!";
            $allow_update = false;
        } else {
            // Kiểm tra mật khẩu cũ có khớp với Database không
            if (password_verify($current_password, $user['password'])) {
                $update_pass = true;
            } else {
                $error = "Mật khẩu hiện tại không chính xác!";
                $allow_update = false;
            }
        }
    }

    // Tiến hành cập nhật nếu không có lỗi
    if ($allow_update) {
        if ($update_pass) {
            // Cập nhật TÊN + MẬT KHẨU MỚI
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, password = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("ssi", $name, $hashed_password, $user_id);
        } else {
            // Chỉ cập nhật TÊN (Giữ nguyên mật khẩu cũ)
            $stmt = $conn->prepare("UPDATE users SET name = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("si", $name, $user_id);
        }

        if ($stmt->execute()) {
            $message = "Cập nhật thông tin thành công!";
            $_SESSION['name'] = $name; // Cập nhật session tên hiển thị
            
            // Refresh lại dữ liệu user để hiển thị đúng ngay lập tức
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Lỗi hệ thống: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Hồ sơ cá nhân</title>
<style>
/* =========================================
   THEME FERRARI (ĐỎ - ĐEN - VÀNG)
   ========================================= */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Arial', sans-serif;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: #1c1c1c;
  color: #fff;
}

/* Header */
.header {
  background: linear-gradient(90deg, #ff0000, #900000);
  color: #fff;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}

.header h1 { font-size: 28px; font-weight: bold; display: flex; align-items: center; }
.header h1 i { margin-right: 10px; color: #ffcc00; }

.header .logout-btn {
  background: #b30000; color: #fff; padding: 8px 16px; border: 1px solid #b30000;
  border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s;
}
.header .logout-btn:hover {
  background: #ffcc00; color: #900000; border-color: #ffcc00; transform: scale(1.05);
}

/* Layout */
.container { display: flex; flex: 1; }

/* Sidebar */
.sidebar {
  width: 220px; padding: 20px; background: #900000; border-right: 2px solid #ff0000;
}
.sidebar h2 { margin-bottom: 20px;
  color: #ffcc00;
  border-bottom: 1px solid rgba(255,255,255,0.2);
  padding-bottom: 10px;}
.sidebar a {
  display: block; color: #fff; text-decoration: none; margin: 12px 0;
  font-weight: bold; padding: 8px; border-radius: 4px; transition: 0.3s;
}
.sidebar a:hover { background: #ff0000; padding-left: 15px; }

.sidebar a.active {
  background: #ffcc00;
  color: #900000;
  padding-left: 15px;
  border-left: 5px solid #fff;
  box-shadow: 0 0 10px rgba(255, 204, 0, 0.5);
}
/* Main Content */
.main-content { flex: 1; padding: 20px; background: #1c1c1c; overflow: auto; }

/* Profile Form Card */
.profile-card {
  background: #fff;
  color: #000;
  padding: 30px;
  border-radius: 8px;
  max-width: 600px;
  margin: 0 auto;
  box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
  border-top: 5px solid #ff0000;
}

.profile-card h2 { color: #900000; margin-bottom: 20px; text-align: center; text-transform: uppercase; }

label { font-weight: bold; display: block; margin: 15px 0 5px; color: #333; }
input {
  width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;
  font-size: 16px;
}
input:focus { border-color: #ff0000; outline: none; }

/* Style cho ô readonly (Email) */
input[readonly] {
    background-color: #e9ecef;
    color: #495057;
    cursor: not-allowed;
}

button.save-btn {
  width: 100%; padding: 12px; margin-top: 25px;
  background: #ffcc00; color: #900000;
  border: none; font-weight: bold; font-size: 16px;
  border-radius: 4px; cursor: pointer; transition: 0.3s;
}
button.save-btn:hover { background: #ff0000; color: #fff; }

.alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; }
.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

footer { background: #900000; color: #ffcc00; text-align: center; padding: 12px 0; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
  <h1><img src="https://cdn-icons-png.freepik.com/512/6475/6475938.png" alt="icon" style="width:30px; vertical-align:middle; margin-right:10px;"><i class="fas fa-user-circle"></i> Quản lý tiền lương</h1>
  <a href="logout.php" class="logout-btn">Đăng xuất</a>
</div>

<div class="container">
  <div class="sidebar">
    <h2>Menu</h2>
    <a href="report.php">Báo Cáo</a>
      <a href="profile.php"class="active">Tài khoản của tôi</a>
      <a href="chatbot.php">CHATBOT AI</a>
      <a href="index.php">Trang chủ</a>
      <a href="employee.php">Nhân viên</a>
      <a href="pay.php">Tiền lương</a>
      <a href="right.php">Phân Quyền</a>
      <a href="subsidy.php">Phụ Cấp</a>
      <a href="role.php">Chức Vụ</a>
      <a href="department.php">Phòng Ban</a>
      <a href="attendance.php">Chấm Công</a>
    </div>

  <div class="main-content">
    
    <div class="profile-card">
      <h2>Cập nhật thông tin</h2>
      
      <?php if($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
      <?php endif; ?>
      
      <?php if($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <label>Tên hiển thị:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Email đăng nhập (Không thể thay đổi):</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
        <p style="color: #900000; font-weight: bold; font-style: italic;">Đổi mật khẩu (Bỏ trống nếu không đổi)</p>

        <label>Mật khẩu hiện tại (*):</label>
        <input type="password" name="current_password" placeholder="Nhập mật khẩu cũ để xác nhận...">

        <label>Mật khẩu mới:</label>
        <input type="password" name="new_password" placeholder="Nhập mật khẩu mới...">
        
        <p style="margin-top: 10px; font-size: 12px; color: #666;">
          * Ngày tạo tài khoản: <?php echo $user['created_at']; ?><br>
          * Cập nhật lần cuối: <?php echo $user['updated_at']; ?>
        </p>

        <button type="submit" class="save-btn">Lưu thay đổi</button>
      </form>
    </div>

  </div>
</div>

<footer>
  &copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.
</footer>

</body>
</html>