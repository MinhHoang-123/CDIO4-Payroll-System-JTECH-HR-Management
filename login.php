<?php
session_start();
include 'connect.php'; // Đảm bảo file này tồn tại

// Kiểm tra biến $conn từ connect.php
if (!isset($conn)) {
    // Nếu connect.php chưa khởi tạo $conn, hoặc lỗi
    die("Lỗi: Không tìm thấy biến kết nối CSDL (\$conn).");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Sử dụng Prepared Statement để chống SQL Injection
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  
  if ($stmt) {
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu (Giả sử trong CSDL mật khẩu đã được mã hóa bằng password_hash)
        if (password_verify($password, $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['name'] = $user['name'];
          header("Location: index.php");
          exit();
        } else {
          $error = "Mật khẩu không đúng.";
        }
      } else {
        $error = "Email không tồn tại.";
      }
      $stmt->close();
  } else {
      $error = "Lỗi truy vấn SQL.";
  }
  
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập hệ thống</title>
  <style>
    /* Reset mặc định */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* --- PHẦN QUAN TRỌNG: ẢNH NỀN --- */
    body {
      font-family: 'Arial', sans-serif;
      
      /* Cấu hình Flexbox để căn giữa */
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
      color: #fff;

      /* Cấu hình Ảnh nền */
      /* Thay 'duong-dan-anh.jpg' bằng link ảnh của bạn (Ví dụ: 'background.jpg' hoặc link online) */
      /* rgba(0,0,0,0.7) là lớp phủ màu đen mờ 70% để làm nổi bật Form */
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://wallpapers.com/images/hd/money-heist-in-4k-free-fire-xn2u3qz96g6ac7vk.jpg');
      
      /* Giúp ảnh luôn full màn hình và không bị méo */
      background-size: cover; 
      background-position: center;
      background-repeat: no-repeat;
    }

    /* Hộp đăng nhập */
    .login-box {
      background: rgba(0, 0, 0, 0.85); /* Làm nền hộp trong suốt nhẹ để thấy ảnh mờ phía sau */
      padding: 40px;
      border-radius: 8px;
      width: 100%;
      max-width: 400px;
      
      /* Viền và Hiệu ứng phát sáng màu Đỏ */
      border: 1px solid #900000;
      box-shadow: 0 0 20px rgba(255, 0, 0, 0.5); /* Tăng độ sáng shadow lên xíu */
    }

    .login-box h2 {
      margin-bottom: 30px;
      text-align: center;
      color: #ffcc00; /* Vàng Ferrari */
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Ô nhập liệu */
    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      background: #333;
      border: 1px solid #555;
      color: #fff;
      border-radius: 4px;
      outline: none;
      transition: 0.3s;
    }

    .login-box input:focus {
      border-color: #ff0000;
      background: #222;
    }

    /* Nút Đăng nhập */
    .login-box button {
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

    .login-box button:hover {
      background: #ff0000;
      color: #fff;
      transform: scale(1.02);
    }

    /* Link */
    .login-box a {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #ccc;
      text-decoration: none;
      font-size: 14px;
      transition: 0.3s;
    }

    .login-box a:hover {
      color: #ffcc00;
      text-decoration: underline;
    }

    /* Thông báo lỗi */
    .error {
      color: #ff4d4d;
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
      background: rgba(255, 0, 0, 0.2);
      padding: 10px;
      border-radius: 4px;
      border: 1px solid #ff0000;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Đăng nhập</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Đăng nhập</button>
      <a href="register.php">Chưa có tài khoản? Đăng ký</a>
    </form>
  </div>
</body>
</html>