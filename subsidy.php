<?php
session_start();
require_once 'connect.php';
require_once 'permission_functions.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Lấy user hiện tại
$user_id = $_SESSION['user_id'] ?? 0;

// Quyền cho trang
$can_view   = checkPermission($user_id, 'subsidy.php', 'can_view', $conn);
$can_add    = checkPermission($user_id, 'subsidy.php', 'can_add', $conn);
$can_delete = checkPermission($user_id, 'subsidy.php', 'can_delete', $conn);

// Không có quyền xem
if (!$can_view) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
          <style>body{font-family:Arial;text-align:center;padding:50px;}h1{color:red;}</style>
          </head><body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p>
          <a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}

// =====================
// XỬ LÝ PHỤ CẤP (Thêm/Sửa)
// =====================
if ($can_add && isset($_POST['add_phucap'])) {
    $tenphucap = $_POST['tenphucap'];
    $sotien = $_POST['sotien'];
    $ghichu = $_POST['ghichu'];
    $created_at = date("Y-m-d H:i:s");
    $updated_at = date("Y-m-d H:i:s");
    $id_edit = $_POST['id_phucap_edit'] ?? 0;

    if ($id_edit > 0) {
        // Sửa
        $stmt = $conn->prepare("UPDATE phu_caps SET tenphucap=?, sotien=?, ghichu=?, updated_at=? WHERE id_phucap=?");
        $stmt->bind_param("sissi", $tenphucap, $sotien, $ghichu, $updated_at, $id_edit);
        $stmt->execute();
    } else {
        // Thêm mới
        $stmt = $conn->prepare("INSERT INTO phu_caps (tenphucap, sotien, ghichu, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $tenphucap, $sotien, $ghichu, $created_at);
        $stmt->execute();
    }
}

// =====================
// XỬ LÝ GÁN/SỬA PHỤ CẤP NHÂN VIÊN
// =====================
if ($can_add && isset($_POST['add_subsidy'])) {
    $id_nhanvien = intval($_POST['id_nhanvien']);
    $id_phucap   = intval($_POST['id_phucap']);
    $ngaybatdau  = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];

    // Kiểm tra đã tồn tại chưa
    $check = $conn->query("SELECT * FROM nhan_vien_phu_caps WHERE id_nhanvien=$id_nhanvien AND id_phucap=$id_phucap");
    if ($check->num_rows > 0) {
        // Cập nhật
        $stmt = $conn->prepare("UPDATE nhan_vien_phu_caps SET ngaybatdau=?, ngayketthuc=? WHERE id_nhanvien=? AND id_phucap=?");
        $stmt->bind_param("ssii", $ngaybatdau, $ngayketthuc, $id_nhanvien, $id_phucap);
        $stmt->execute();
    } else {
        // Thêm mới
        $stmt = $conn->prepare("INSERT INTO nhan_vien_phu_caps (id_nhanvien, id_phucap, ngaybatdau, ngayketthuc) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_nhanvien, $id_phucap, $ngaybatdau, $ngayketthuc);
        $stmt->execute();
    }
}

// =====================
// XÓA PHỤ CẤP NHÂN VIÊN
// =====================
if ($can_delete && isset($_GET['delete_subsidy'])) {
    $id_nv = intval($_GET['id_nhanvien']);
    $id_pc = intval($_GET['id_phucap']);
    $conn->query("DELETE FROM nhan_vien_phu_caps WHERE id_nhanvien=$id_nv AND id_phucap=$id_pc");
}

// XÓA PHỤ CẤP
if ($can_delete && isset($_GET['delete_phucap'])) {
    $id_pc = intval($_GET['id_phucap']);
    $conn->query("DELETE FROM phu_caps WHERE id_phucap=$id_pc");
}

// =====================
// LẤY DỮ LIỆU & PHÂN TRANG
// =====================

// 1. Cấu hình phân trang cho danh sách đã gán
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// 2. Đếm tổng số bản ghi
$count_sql = "SELECT COUNT(*) as total FROM nhan_vien_phu_caps";
$total_res = $conn->query($count_sql);
$total_records = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// 3. Truy vấn dữ liệu có LIMIT
$nhanvien_result = $conn->query("SELECT id_nhanvien, hoten FROM nhan_viens");
$phucap_result   = $conn->query("SELECT id_phucap, tenphucap, sotien FROM phu_caps");

$list_result     = $conn->query("
    SELECT nvpc.*, nv.hoten, pc.tenphucap, pc.sotien 
    FROM nhan_vien_phu_caps nvpc
    JOIN nhan_viens nv ON nvpc.id_nhanvien = nv.id_nhanvien
    JOIN phu_caps pc ON nvpc.id_phucap = pc.id_phucap
    ORDER BY nvpc.ngaybatdau DESC
    LIMIT $offset, $limit
");

$ds_phucap = $conn->query("SELECT * FROM phu_caps ORDER BY id_phucap DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý phụ cấp</title>
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
  padding: 15px 30px; /* Tăng padding chút cho thoáng */
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}

/* NÚT LOGOUT (ĐÃ UPDATE THEO YÊU CẦU) */
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

/* Hiệu ứng Hover chuẩn Ferrari: Nền Vàng - Chữ Đỏ */
.header .logout-btn:hover {
  background: #ffcc00;
  color: #900000;
  border-color: #ffcc00;
  transform: scale(1.05);
}

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
  margin: 10px 0;
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

/* =========================================
   PHẦN 2: TABLE & FORM (GIỮ MÀU SÁNG CHO DỄ NHÌN)
   ========================================= */

/* Tiêu đề Section: Thay màu xanh cũ bằng màu Vàng Ferrari */
h2.section-title {
  background: #ffcc00; /* Vàng */
  color: #900000;      /* Chữ Đỏ */
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
  color: #000; /* Chữ đen */
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

/* Table nền trắng */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  color: #000; /* Chữ đen */
  margin-bottom: 10px; /* Giảm margin để gắn pagination */
}

th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

th {
  background: #bdc3c7; /* Header bảng màu xám cho dịu mắt */
  color: #000;
}

/* Input & Button chung */
input, select, textarea {
  padding: 8px 10px;
  margin: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  padding: 8px 16px;
  margin: 5px;
  background: #ffcc00; /* Nút mặc định màu Vàng */
  color: #900000;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  transition: 0.3s;
}

button:hover {
  background: #ff0000;
  color: #fff;
}

/* Nút xóa (Link delete) */
.btn-delete {
  color: #e74c3c;
  text-decoration: none;
  font-weight: bold;
}

.btn-delete:hover {
  text-decoration: underline;
  color: #ff0000;
}

/* PHÂN TRANG */
.pagination { margin-bottom: 30px; text-align: center; }
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
      <a href="subsidy.php" class="active">Phụ Cấp</a>
      <a href="role.php">Chức Vụ</a>
      <a href="department.php">Phòng Ban</a>
      <a href="attendance.php">Chấm Công</a>
    </div>

<div class="main-content">

<?php if($can_add): ?>
<h2 class="section-title">Thêm/Sửa phụ cấp</h2>
<form method="POST" id="form_phucap">
    <input type="hidden" name="id_phucap_edit" value="">
    <label>Tên phụ cấp:</label>
    <input type="text" name="tenphucap" required>
    <label>Số tiền:</label>
    <input type="number" name="sotien" required>
    <label>Ghi chú:</label>
    <textarea name="ghichu"></textarea>
    <button type="submit" name="add_phucap">Lưu phụ cấp</button>
</form>
<?php endif; ?>

<?php if($can_add): ?>
<h2 class="section-title">Gán/Sửa phụ cấp cho nhân viên</h2>
<form method="POST" id="form_subsidy">
    <label>Nhân viên:</label>
    <select name="id_nhanvien" required>
        <?php while ($nv = $nhanvien_result->fetch_assoc()): ?>
            <option value="<?= $nv['id_nhanvien'] ?>"><?= $nv['hoten'] ?></option>
        <?php endwhile; ?>
    </select>
    <label>Phụ cấp:</label>
    <select name="id_phucap" required>
        <?php mysqli_data_seek($phucap_result, 0); while($pc = $phucap_result->fetch_assoc()): ?>
            <option value="<?= $pc['id_phucap'] ?>"><?= $pc['tenphucap'] ?> - <?= number_format($pc['sotien']) ?>đ</option>
        <?php endwhile; ?>
    </select>
    <label>Bắt đầu:</label>
    <input type="date" name="ngaybatdau" required>
    <label>Kết thúc:</label>
    <input type="date" name="ngayketthuc" required>
    <button type="submit" name="add_subsidy">Lưu gán phụ cấp</button>
</form>
<?php endif; ?>

<h2 class="section-title">Danh sách phụ cấp đã gán</h2>
<table>
<thead>
<tr>
<th>ID NV</th><th>Tên NV</th><th>Phụ cấp</th><th>Số tiền</th>
<th>Bắt đầu</th><th>Kết thúc</th><th>Hành động</th>
</tr>
</thead>
<tbody>
<?php while($row = $list_result->fetch_assoc()): ?>
<tr>
<td><?= $row['id_nhanvien'] ?></td>
<td><?= $row['hoten'] ?></td>
<td><?= $row['tenphucap'] ?></td>
<td><?= number_format($row['sotien']) ?>đ</td>
<td><?= $row['ngaybatdau'] ?></td>
<td><?= $row['ngayketthuc'] ?></td>
<td>
<?php if($can_add): ?>
<a href="#" onclick="editSubsidy(<?= $row['id_nhanvien'] ?>,<?= $row['id_phucap'] ?>,'<?= $row['ngaybatdau'] ?>','<?= $row['ngayketthuc'] ?>')">Sửa</a>
<?php endif; ?>
<?php if($can_delete): ?>
| <a class="btn-delete" href="?delete_subsidy=1&id_nhanvien=<?= $row['id_nhanvien'] ?>&id_phucap=<?= $row['id_phucap'] ?>" onclick="return confirm('Xoá phụ cấp này?')">Xoá</a>
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
<h2 class="section-title">Danh sách các phụ cấp</h2>
<table>
<thead>
<tr>
<th>ID</th><th>Tên phụ cấp</th><th>Số tiền</th><th>Ghi chú</th><th>Ngày tạo</th><th>Ngày cập nhật</th><th>Hành động</th>
</tr>
</thead>
<tbody>
<?php while($pc = $ds_phucap->fetch_assoc()): ?>
<tr>
<td><?= $pc['id_phucap'] ?></td>
<td><?= $pc['tenphucap'] ?></td>
<td><?= number_format($pc['sotien']) ?>đ</td>
<td><?= $pc['ghichu'] ?></td>
<td><?= $pc['created_at'] ?></td>
<td><?= $pc['updated_at'] ?></td>
<td>
<?php if($can_add): ?>
<a href="#" onclick="editPhuCap(<?= $pc['id_phucap'] ?>,'<?= $pc['tenphucap'] ?>',<?= $pc['sotien'] ?>,'<?= $pc['ghichu'] ?>')">Sửa</a>
<?php endif; ?>
<?php if($can_delete): ?>
| <a class="btn-delete" href="?delete_phucap=1&id_phucap=<?= $pc['id_phucap'] ?>" onclick="return confirm('Xoá phụ cấp này?')">Xoá</a>
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
function editPhuCap(id, ten, tien, ghichu) {
    const form = document.getElementById('form_phucap');
    form.id_phucap_edit.value = id;
    form.tenphucap.value = ten;
    form.sotien.value = tien;
    form.ghichu.value = ghichu;
    form.scrollIntoView({behavior:'smooth'});
}
function editSubsidy(id_nv, id_pc, ngaybd, ngaykt) {
    const form = document.getElementById('form_subsidy');
    form.id_nhanvien.value = id_nv;
    form.id_phucap.value = id_pc;
    form.ngaybatdau.value = ngaybd;
    form.ngayketthuc.value = ngaykt;
    form.scrollIntoView({behavior:'smooth'});
}
</script>

</body>
</html>