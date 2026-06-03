<?php
session_start();
require_once 'connect.php';
require_once 'permission_functions.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');

$page_name = 'report.php';

// =====================
// 1. KIỂM TRA ĐĂNG NHẬP & QUYỀN
// =====================
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$can_view   = checkPermission($user_id, $page_name, 'can_view', $conn);
$can_export = checkPermission($user_id, $page_name, 'can_add', $conn); // Giả sử quyền export là can_add

if (!$can_view) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
          <style>body{font-family:Arial;text-align:center;padding:50px;}h1{color:red;}</style>
          </head><body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p>
          <a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}

// =====================
// 2. LẤY THAM SỐ & CẤU HÌNH PHÂN TRANG
// =====================
$thang = isset($_GET['thang']) ? (int)$_GET['thang'] : date('m');
$nam   = isset($_GET['nam']) ? (int)$_GET['nam'] : date('Y');

// Cấu hình phân trang
$limit = 10; // Số dòng mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// =====================
// 3. TRUY VẤN DỮ LIỆU
// =====================

// Bước 1: Đếm tổng số bản ghi để tính số trang
$sql_count = "SELECT COUNT(*) as total FROM luongs WHERE thang = $thang AND nam = $nam";
$res_count = $conn->query($sql_count);
$total_records = $res_count->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Bước 2: Lấy dữ liệu chi tiết có LIMIT và OFFSET
$sql_data = "SELECT l.*, nv.hoten 
             FROM luongs l 
             JOIN nhan_viens nv ON l.id_nhanvien = nv.id_nhanvien 
             WHERE l.thang = $thang AND l.nam = $nam
             ORDER BY l.tongluong DESC 
             LIMIT $offset, $limit";
$data = $conn->query($sql_data);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Báo cáo tiền lương - Trang <?= $page ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* ===================== THEME FERRARI ===================== */
* { margin: 0; padding: 0; box-sizing: border-box; }
body { 
    font-family: 'Arial', sans-serif; 
    min-height: 100vh; 
    background: #1c1c1c; 
    color: #fff; 
    display: flex;
    flex-direction: column;
}

/* HEADER */
.header { 
    background: linear-gradient(90deg, #ff0000, #900000); 
    padding: 15px 30px; 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.5); 
}
.header h1 { display: flex; align-items: center; font-size: 24px; font-weight: bold; }
.header img { width: 30px; margin-right: 10px; }
.logout-btn { 
    background: #b30000; color: #fff; padding: 8px 16px; border-radius: 4px; 
    text-decoration: none; font-weight: bold; transition: 0.3s; 
}
.logout-btn:hover { background: #ffcc00; color: #900000; }

.container { display: flex; flex: 1; }

/* SIDEBAR */
.sidebar { width: 220px; background: #900000; padding: 20px; border-right: 2px solid #ff0000; }
.sidebar h2 { color: #ffcc00; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px;}
.sidebar a { display: block; color: #fff; margin: 10px 0; text-decoration: none; font-weight: bold; padding: 8px; border-radius: 4px; transition: 0.3s; }
.sidebar a:hover { background: #ff0000; padding-left: 15px; }
.sidebar a.active { background: #ffcc00; color: #900000; padding-left: 15px; border-left: 5px solid #fff; box-shadow: 0 0 10px rgba(255, 204, 0, 0.5); }

/* MAIN CONTENT */
.main-content { flex: 1; padding: 20px; overflow: auto; }
.section-title { 
    color: #ffcc00; 
    margin-bottom: 20px; 
    font-weight: bold; 
    font-size: 24px;
}

/* FORM */
form { background: #333; color: #fff; padding: 15px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-left: 5px solid #ffcc00; }
input { padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; color: #000; font-weight: bold; width: 80px;}
button, .export-btn { padding: 6px 12px; border-radius: 4px; border: none; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block;}
button { background: #ffcc00; color: #900000; }
button:hover { background: #ff0000; color: #fff; }
.export-btn { background: #008000; color: #fff; margin-left: 5px; font-size: 14px;}
.export-btn.pdf { background: #b30000; }
.export-btn:hover { opacity: 0.8; }

/* TABLE */
table { width: 100%; background: #fff; color: #000; border-collapse: collapse; margin-top: 10px; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #bdc3c7; font-weight: bold; }
tr:hover { background-color: #f1f1f1; }

/* PAGINATION */
.pagination { margin-top: 20px; display: flex; justify-content: center; gap: 5px; }
.pagination a {
    color: #fff; background: #333; padding: 8px 12px; text-decoration: none;
    border-radius: 4px; border: 1px solid #555; font-weight: bold; transition: 0.3s;
}
.pagination a:hover { background: #ff0000; border-color: #ff0000; }
.pagination a.active { background: #ffcc00; color: #900000; border-color: #ffcc00; }
.pagination span { color: #aaa; padding: 8px 12px; }

footer { background: #900000; color: #ffcc00; text-align: center; padding: 10px; margin-top: auto; font-weight: bold; }
</style>
</head>

<body>

<div class="header">
    <h1><img src="https://cdn-icons-png.freepik.com/512/6475/6475938.png"> Báo cáo tiền lương</h1>
    <a href="logout.php" class="logout-btn">Đăng xuất</a>
</div>

<div class="container">
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="report.php" class="active">Báo Cáo</a>
        <a href="profile.php">Tài khoản của tôi</a>
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

        <h2 class="section-title">
             Báo cáo lương tháng <?= $thang ?>/<?= $nam ?>
        </h2>

        <form method="GET">
            <label>Tháng:</label>
            <input type="number" name="thang" value="<?= $thang ?>" min="1" max="12">
            <label>Năm:</label>
            <input type="number" name="nam" value="<?= $nam ?>">
            <button type="submit">Xem báo cáo</button>

            <?php if ($can_export): ?>
                <div style="margin-left: auto;">
                    <a class="export-btn" href="export_report_excel.php?thang=<?= $thang ?>&nam=<?= $nam ?>">Xuất Excel</a>
                    <a class="export-btn pdf" href="export_report_pdf.php?thang=<?= $thang ?>&nam=<?= $nam ?>">Xuất PDF</a>
                </div>
            <?php endif; ?>
        </form>

        <table>
            <thead>
                <tr>
                    <th>STT</th> <th>Nhân viên</th>
                    <th>Lương CB</th>
                    <th>Phụ cấp</th>
                    <th>Thưởng</th>
                    <th>Phạt</th>
                    <th>Thuế</th>
                    <th>Thực nhận</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data && $data->num_rows > 0): ?>
                    <?php 
                    $stt = $offset + 1; // Tính STT liên tục qua các trang
                    while ($r = $data->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?= $stt++ ?></td>
                        <td style="text-align: left; font-weight: bold;"><?= htmlspecialchars($r['hoten']) ?></td>
                        <td><?= number_format($r['luongcoban']) ?></td>
                        <td><?= number_format($r['tongphucap']) ?></td>
                        <td><?= number_format($r['tongthuong']) ?></td>
                        <td><?= number_format($r['tongphat']) ?></td>
                        <td><?= number_format($r['thue']) ?></td>
                        <td style="color:#b30000; font-weight:bold; font-size: 1.1em;"><?= number_format($r['tongluong']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="padding: 20px;">
                            <b>Không có dữ liệu lương cho tháng <?= $thang ?>/<?= $nam ?></b><br>
                            <small>Vui lòng kiểm tra lại bộ lọc hoặc tính lương bên trang Tiền lương.</small>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php 
            // Link cơ sở giữ nguyên tham số tháng/năm
            $base_link = "?thang=$thang&nam=$nam"; 
            
            if ($page > 1): ?>
                <a href="<?= $base_link ?>&page=1">« Đầu</a>
                <a href="<?= $base_link ?>&page=<?= $page - 1 ?>">‹ Trước</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                    <a href="<?= $base_link ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                    <span>...</span>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="<?= $base_link ?>&page=<?= $page + 1 ?>">Sau ›</a>
                <a href="<?= $base_link ?>&page=<?= $total_pages ?>">Cuối »</a>
            <?php endif; ?>
        </div>
        <div style="text-align: center; margin-top: 10px; color: #aaa; font-size: 0.9em;">
            Hiển thị trang <?= $page ?> / <?= $total_pages ?> (Tổng <?= $total_records ?> nhân viên)
        </div>
        <?php endif; ?>

    </div>
</div>

<footer>
    © 2025 Công ty J-TECH. Mọi quyền được bảo lưu.
</footer>

</body>
</html>