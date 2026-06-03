<?php
session_start();
session_destroy(); // Xoá toàn bộ session (hủy đăng nhập)
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng xuất thành công</title>
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
  
  /* Cấu hình Flexbox căn giữa */
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  color: #fff;

  /* Cấu hình Ảnh nền (Giống trang Login) */
  /* Thay link ảnh bên dưới bằng ảnh của bạn */
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://i.pinimg.com/originals/c1/3e/7f/c13e7f371b14f93e91808200631b8a81.gif');
  
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* Hộp thông báo (Message Card) */
.message {
  text-align: center;
  /* Chuyển nền đen đặc #000 sang đen trong suốt để thấy ảnh nền mờ phía sau */
  background: rgba(0, 0, 0, 0.85); 
  padding: 40px;
  border-radius: 8px;
  
  /* Viền đỏ và hiệu ứng phát sáng */
  border: 1px solid #900000;
  box-shadow: 0 0 20px rgba(255, 0, 0, 0.5); /* Tăng độ sáng shadow lên xíu */
  
  max-width: 500px;
  width: 90%; /* Responsive trên mobile */
}

/* Tiêu đề thông báo */
.message h2 {
  color: #ffcc00; /* Vàng Ferrari */
  font-size: 24px;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Nội dung text phụ */
.message p {
  color: #ccc;
  margin-bottom: 20px;
}

/* Nút quay lại/Tiếp tục */
.message a {
  display: inline-block;
  margin-top: 10px;
  padding: 12px 30px;
  background-color: #ffcc00; /* Nền Vàng */
  color: #900000;          /* Chữ Đỏ */
  text-decoration: none;
  border-radius: 6px;
  font-weight: bold;
  text-transform: uppercase;
  transition: 0.3s;
}

/* Hiệu ứng Hover */
.message a:hover {
  background-color: #ff0000; /* Chuyển sang Đỏ */
  color: #fff;               /* Chữ Trắng */
  transform: scale(1.05);    /* Phóng to nhẹ */
  box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
}
</style>
</head>
<body>
  <div class="message">
    <h2>Bạn đã đăng xuất thành công!</h2>
    <p>Hẹn gặp lại bạn trong phiên làm việc tiếp theo.</p>
    <a href="login.php">Đăng nhập lại</a>
  </div>
</body>
</html>