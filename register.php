<?php
session_start();
include __DIR__ . '/connect.php'; // dùng đường dẫn tuyệt đối

// Kiểm tra kết nối tồn tại chưa
if (!isset($conn)) {
    die("Lỗi: Không kết nối được CSDL.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Kiểm tra trùng tên/email
  $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? OR email = ?");
  $stmt->bind_param("ss", $name, $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $error = "Tên đăng nhập hoặc email đã tồn tại!";
  } else {
    // Thêm user mới
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
      header("Location: login.php");
      exit();
    } else {
      $error = "Lỗi khi tạo tài khoản: " . $stmt->error;
    }
  }

  $stmt->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký thành viên</title>
  <style>
/* Reset mặc định */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* --- PHẦN QUAN TRỌNG: ẢNH NỀN --- */
body {
  font-family: 'Arial', sans-serif;
  
  /* Căn giữa */
  display: flex;
  height: 100vh;
  justify-content: center;
  align-items: center;
  color: #fff;

  /* Cấu hình Ảnh nền (Đồng bộ với Login) */
  /* Thay link ảnh của bạn vào url('...') */
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://scontent.fdad1-2.fna.fbcdn.net/v/t1.15752-9/589817402_2071199967027054_7732448583588275378_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=9f807c&_nc_ohc=zUBhwH0aCLUQ7kNvwHh0tzd&_nc_oc=Adk0MR6ubQtMb2K8c8_HjME4rGTVKaghraq7YP7rYDLeou6gi3QS_rPXv1KDgffheHg&_nc_zt=23&_nc_ht=scontent.fdad1-2.fna&oh=03_Q7cD4AE8W0pZuVA0PkeuJuLeos536Aef7FJwdrIVSp3Fd1bzIA&oe=69562689');
  
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* Hộp đăng ký */
.register-box {
  /* Chuyển nền đen đặc sang đen trong suốt */
  background: rgba(0, 0, 0, 0.85);
  padding: 40px;
  border-radius: 8px;
  width: 100%;
  max-width: 400px; /* Giới hạn chiều rộng */
  
  /* Viền và Hiệu ứng phát sáng màu Đỏ */
  border: 1px solid #900000;
  box-shadow: 0 0 20px rgba(255, 0, 0, 0.5); /* Tăng độ sáng shadow */
}

.register-box h2 {
  margin-bottom: 30px;
  text-align: center;
  color: #ffcc00; /* Tiêu đề màu Vàng */
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Ô nhập liệu */
.register-box input {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  background: #333; /* Nền input xám tối */
  border: 1px solid #555;
  color: #fff; /* Chữ trắng */
  border-radius: 4px;
  outline: none;
  transition: 0.3s;
}

/* Khi bấm vào ô nhập liệu -> Viền đỏ */
.register-box input:focus {
  border-color: #ff0000;
  background: #222;
}

/* Nút Đăng ký (Thay thế màu xanh cũ bằng Vàng Ferrari) */
.register-box button {
  width: 100%;
  padding: 12px;
  margin-top: 20px;
  background: #ffcc00; /* Nền Vàng */
  color: #900000;      /* Chữ Đỏ */
  border: none;
  font-weight: bold;
  cursor: pointer;
  border-radius: 4px;
  text-transform: uppercase;
  transition: 0.3s;
}

/* Hover vào nút -> Chuyển sang Đỏ */
.register-box button:hover {
  background: #ff0000;
  color: #fff;
  transform: scale(1.02);
}

/* Link Đăng nhập */
.register-box a {
  display: block;
  text-align: center;
  margin-top: 20px;
  color: #ccc;
  text-decoration: none;
  font-size: 14px;
  transition: 0.3s;
}

.register-box a:hover {
  color: #ffcc00;
  text-decoration: underline;
}

/* Thông báo lỗi */
.error {
  color: #ff4d4d; /* Đỏ tươi */
  text-align: center;
  margin-bottom: 15px;
  background: rgba(255, 0, 0, 0.2);
  padding: 10px;
  border-radius: 4px;
  font-weight: bold;
  border: 1px solid #ff0000;
}
</style>
</head>
<body>
  <div class="register-box">
    <h2>Đăng ký</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
      <input type="text" name="name" placeholder="Tên đăng nhập" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Tạo tài khoản</button>
      <a href="login.php">Đã có tài khoản? Đăng nhập</a>
    </form>
  </div>
</body>
</html>