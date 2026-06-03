<?php
session_start();
include 'connect.php';
include 'permission_functions.php';

$page_name = 'pay.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Kiểm tra quyền xem
if (!checkPermission($user_id, $page_name, 'can_view', $conn)) {
    echo "<!DOCTYPE html><html lang='vi'><head><meta charset='UTF-8'><title>Lỗi</title>
          <style>body{font-family:Arial;text-align:center;padding:50px;}h1{color:red;}</style>
          </head><body><h1>LỖI TRUY CẬP</h1><p>Bạn không có quyền xem trang này!</p>
          <a href='index.php'>Quay về Trang chủ</a></body></html>";
    exit();
}

// 3. Lấy các quyền khác
$can_add = checkPermission($user_id, $page_name, 'can_add', $conn);
$can_delete = checkPermission($user_id, $page_name, 'can_delete', $conn);

// 4. Lấy tham số lọc cơ bản
$view  = $_GET['view']  ?? 'pay';
$thang = (int)($_GET['thang'] ?? date('m'));
$nam   = (int)($_GET['nam']   ?? date('Y'));

// Lấy giá trị lọc phòng ban/chức vụ
$filter_pb = isset($_GET['id_phongban']) && $_GET['id_phongban'] !== '' ? (int)$_GET['id_phongban'] : null;
$filter_cv = isset($_GET['id_chucvu']) && $_GET['id_chucvu'] !== '' ? (int)$_GET['id_chucvu'] : null;

// HÀM TÍNH THUẾ
function tinhThue($thuNhap) {
    if ($thuNhap <= 5000000) return $thuNhap * 0.05;
    elseif ($thuNhap <= 10000000) return $thuNhap * 0.10;
    elseif ($thuNhap <= 18000000) return $thuNhap * 0.15;
    elseif ($thuNhap <= 32000000) return $thuNhap * 0.20;
    elseif ($thuNhap <= 52000000) return $thuNhap * 0.25;
    elseif ($thuNhap <= 80000000) return $thuNhap * 0.30;
    else return $thuNhap * 0.35;
}

// CẤU HÌNH PHÂN TRANG (PAGINATION)
$limit = 10; // Số dòng trên 1 trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý lương nhân viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
/* =========================================
   THEME FERRARI (Đỏ - Vàng - Đen)
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

.header {
  background: linear-gradient(90deg, #ff0000, #900000);
  color: #fff;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0,0,0,0.5);
}

.header .logout-btn {
  background: #b30000; color: #fff; border: 1px solid #b30000;
  padding: 8px 16px; cursor: pointer; border-radius: 6px;
  text-decoration: none; font-weight: bold; transition: 0.3s;
}
.header .logout-btn:hover { background: #ffcc00; color: #900000; border-color: #ffcc00; transform: scale(1.05); }

.container { display: flex; flex: 1; }

.sidebar { width: 220px; padding: 20px; background: #900000; border-right: 2px solid #ff0000; color: #fff; }
.sidebar h2 { margin-bottom: 20px; color: #ffcc00; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 10px; }
.sidebar a { display: block; color: #fff; text-decoration: none; margin: 12px 0; font-weight: bold; padding: 6px 12px; border-radius: 6px; transition: 0.3s; }
.sidebar a:hover { background: #ff0000; color: #fff; }
.sidebar a.active { background: #ffcc00; color: #900000; padding-left: 15px; border-left: 5px solid #fff; box-shadow: 0 0 10px rgba(255, 204, 0, 0.5); }

.main-content { flex: 1; padding: 20px; background: #1c1c1c; }
footer { background: #900000; color: #ffcc00; text-align: center; padding: 12px 0; font-weight: bold; }

/* Table & Form */
table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; color: #000; }
th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
th { background: #bdc3c7; color: #000; }

input, select { padding: 8px; margin: 5px 0; width: 100%; border: 1px solid #ccc; border-radius: 4px; color: #000; }

/* Filter Form */
.filter-form { background: #333; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 15px; align-items: center; border: 1px solid #555; flex-wrap: wrap; }
.filter-item { display: flex; flex-direction: column; }
.filter-item label { color: #fff; font-weight: bold; font-size: 13px; margin-bottom: 3px; }
.w-small { width: 70px; text-align: center; }
.w-medium { width: 180px; }

button { padding: 8px 16px; background: #ffcc00; color: #900000; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; transition: 0.3s; }
button:hover { background: #ff0000; color: #fff; }

.tab-buttons { margin-bottom: 10px; }
.tab-buttons a button { margin-right: 10px; }

/* CSS CHO PHÂN TRANG */
.pagination { margin-top: 20px; display: flex; justify-content: center; gap: 5px; }
.pagination a {
    color: #fff;
    background: #333;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    border: 1px solid #555;
    font-weight: bold;
    transition: 0.3s;
}
.pagination a:hover { background: #ff0000; border-color: #ff0000; }
.pagination a.active { background: #ffcc00; color: #900000; border-color: #ffcc00; }
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
       <a href="pay.php" class="active">Tiền lương</a>
       <a href="right.php">Phân Quyền</a>
       <a href="subsidy.php">Phụ Cấp</a>
       <a href="role.php">Chức Vụ</a>
       <a href="department.php">Phòng Ban</a>
       <a href="attendance.php">Chấm Công</a>
    </div>

    <div class="main-content">
        <h2>Quản lý lương & thưởng/phạt</h2>
        <div class="tab-buttons">
            <a href="pay.php?view=pay&thang=<?= $thang ?>&nam=<?= $nam ?>"><button>Lương</button></a>
            <a href="pay.php?view=bonus&thang=<?= $thang ?>&nam=<?= $nam ?>"><button>Thưởng/Phạt</button></a>
        </div>

        <?php if($view === 'pay') { ?>
            <form method="GET" class="filter-form">
                <input type="hidden" name="view" value="pay">
                <div class="filter-item">
                    <label>Tháng</label>
                    <input type="number" class="w-small" name="thang" value="<?= $thang ?>" min="1" max="12">
                </div>
                <div class="filter-item">
                    <label>Năm</label>
                    <input type="number" class="w-small" name="nam" value="<?= $nam ?>" min="2020">
                </div>
                <div class="filter-item">
                    <label>Phòng ban</label>
                    <select name="id_phongban" class="w-medium">
                        <option value="">-- Tất cả --</option>
                        <?php 
                        $pbs = $conn->query("SELECT * FROM phong_bans");
                        while($p = $pbs->fetch_assoc()) {
                            $selected = ($filter_pb == $p['id_phongban']) ? 'selected' : '';
                            echo "<option value='{$p['id_phongban']}' $selected>{$p['tenphongban']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Chức vụ</label>
                    <select name="id_chucvu" class="w-medium">
                        <option value="">-- Tất cả --</option>
                        <?php 
                        $cvs = $conn->query("SELECT * FROM chuc_vus");
                        while($c = $cvs->fetch_assoc()) {
                            $selected = ($filter_cv == $c['id_chucvu']) ? 'selected' : '';
                            echo "<option value='{$c['id_chucvu']}' $selected>{$c['tenchucvu']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-item" style="justify-content: flex-end;">
                    <label>&nbsp;</label>
                    <button type="submit">Lọc & Xem lương</button>
                </div>
            </form>

            <h3>Bảng lương tháng <?= $thang ?>/<?= $nam ?></h3>
            <table>
            <thead>
            <tr>
                <th>ID</th><th>Tên</th><th>Chức vụ</th><th>Phòng ban</th><th>Số giờ</th><th>Hệ số</th>
                <th>Lương CB</th><th>Phụ cấp</th><th>Thưởng</th><th>Phạt</th><th>Thuế</th><th>Thực nhận</th>
            </tr>
            </thead>
            <tbody>

            <?php
            // 1. TẠO CÂU WHERE CHO BỘ LỌC
            $where_clause = "1=1";
            if ($filter_pb) $where_clause .= " AND nv.id_phongban = $filter_pb";
            if ($filter_cv) $where_clause .= " AND nv.id_chucvu = $filter_cv";

            // 2. ĐẾM TỔNG SỐ BẢN GHI ĐỂ PHÂN TRANG
            $count_sql = "SELECT COUNT(*) as total FROM nhan_viens nv WHERE $where_clause";
            $count_res = $conn->query($count_sql);
            $total_records = $count_res->fetch_assoc()['total'];
            $total_pages = ceil($total_records / $limit);

            // 3. TRUY VẤN LẤY DỮ LIỆU VỚI LIMIT VÀ OFFSET
            $query = "SELECT nv.id_nhanvien, nv.hoten, cv.tenchucvu, cv.hesoluong, pb.tenphongban
                      FROM nhan_viens nv
                      JOIN chuc_vus cv ON nv.id_chucvu = cv.id_chucvu
                      LEFT JOIN phong_bans pb ON nv.id_phongban = pb.id_phongban
                      WHERE $where_clause
                      LIMIT $offset, $limit";
            
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $id_nv = $row['id_nhanvien'];
                    $hoten = $row['hoten'];
                    $chucvu = $row['tenchucvu'];
                    $tenpb = $row['tenphongban'];
                    $hesoluong = $row['hesoluong'];

                    // === KHỞI TẠO BIẾN ===
                    $tong_gio = 0; 
                    $tongphucap = 0;
                    $thuong = 0;
                    $phat = 0;

                    // TÍNH TỔNG GIỜ
                    $cc_query = "SELECT giovao, giora 
                                 FROM cham_congs 
                                 WHERE id_nhanvien=$id_nv 
                                 AND trangthai=1
                                 AND MONTH(giovao) = $thang
                                 AND YEAR(giovao) = $nam";
                    $cc = $conn->query($cc_query);

                    if($cc) {
                        while($c = $cc->fetch_assoc()) {
                            if(!empty($c['giovao']) && !empty($c['giora'])) {
                                $tong_gio += round((strtotime($c['giora']) - strtotime($c['giovao']))/3600, 2);
                            }
                        }
                    }

                    // PHỤ CẤP
                    $pc = $conn->query("SELECT SUM(pc.sotien) AS tongphucap 
                                        FROM nhan_vien_phu_caps nvp
                                        JOIN phu_caps pc ON nvp.id_phucap = pc.id_phucap
                                        WHERE nvp.id_nhanvien=$id_nv");
                    if($pc) {
                        $row_pc = $pc->fetch_assoc();
                        $tongphucap = $row_pc['tongphucap'] ?? 0;
                    }

                    // THƯỞNG/PHẠT
                    $tp = $conn->query("SELECT loai, SUM(sotien) AS tong 
                                        FROM thuong_phats 
                                        WHERE id_nhanvien=$id_nv 
                                        AND MONTH(ngayapdung) = $thang
                                        AND YEAR(ngayapdung) = $nam
                                        GROUP BY loai");
                    if($tp) {
                        while($r = $tp->fetch_assoc()) {
                            if($r['loai']=='thưởng') $thuong = $r['tong'];
                            elseif($r['loai']=='phạt') $phat = $r['tong'];
                        }
                    }

                    // TÍNH TOÁN
                    $tongLuong = ($tong_gio * $hesoluong) + $tongphucap + $thuong - $phat;
                    if($tongLuong < 0) $tongLuong = 0;
                    
                    $thue = tinhThue($tongLuong);
                    $thucnhan = $tongLuong - $thue;

                    // LƯU DATABASE
                    $check = $conn->query("SELECT id_luong FROM luongs WHERE id_nhanvien=$id_nv AND thang=$thang AND nam=$nam");
                    $luong_cb_insert = $tong_gio * $hesoluong;

                    if ($check->num_rows == 0) {
                        $conn->query("INSERT INTO luongs (id_nhanvien, thang, nam, luongcoban, tongphucap, tongthuong, tongphat, thue, tongluong)
                                      VALUES ($id_nv, $thang, $nam, $luong_cb_insert, $tongphucap, $thuong, $phat, $thue, $thucnhan)");
                    } else {
                        $conn->query("UPDATE luongs SET luongcoban=$luong_cb_insert, tongphucap=$tongphucap, tongthuong=$thuong, 
                                      tongphat=$phat, thue=$thue, tongluong=$thucnhan WHERE id_nhanvien=$id_nv AND thang=$thang AND nam=$nam");
                    }

                    echo "<tr>
                    <td>$id_nv</td>
                    <td style='text-align:left;'>$hoten</td>
                    <td>$chucvu</td>
                    <td>$tenpb</td>
                    <td>".number_format($tong_gio, 2)."</td>
                    <td>$hesoluong</td>
                    <td>".number_format($luong_cb_insert)."</td>
                    <td>".number_format($tongphucap)."</td>
                    <td>".number_format($thuong)."</td>
                    <td>".number_format($phat)."</td>
                    <td>".number_format($thue)."</td>
                    <td><strong style='color:#b30000'>".number_format($thucnhan)."</strong></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='12'>Không tìm thấy nhân viên nào phù hợp tiêu chí lọc.</td></tr>";
            }
            ?>
            </tbody>
            </table>
            
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php
                // Tạo link base giữ nguyên các tham số lọc
                $base_link = "pay.php?view=pay&thang=$thang&nam=$nam";
                if($filter_pb) $base_link .= "&id_phongban=$filter_pb";
                if($filter_cv) $base_link .= "&id_chucvu=$filter_cv";

                // Nút Previous
                if ($page > 1) {
                    echo '<a href="' . $base_link . '&page=' . ($page - 1) . '">&laquo; Trước</a>';
                }

                // Các trang số
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($i == $page) ? 'class="active"' : '';
                    echo '<a href="' . $base_link . '&page=' . $i . '" ' . $active . '>' . $i . '</a>';
                }

                // Nút Next
                if ($page < $total_pages) {
                    echo '<a href="' . $base_link . '&page=' . ($page + 1) . '">Sau &raquo;</a>';
                }
                ?>
            </div>
            <?php endif; ?>

        <?php } ?>

        <?php if($view === 'bonus') {
            echo "<h3>Danh sách Thưởng/Phạt</h3>";
            // FORM THƯỞNG PHẠT
            if($can_add && $_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_bonus'])) {
                $id_nv = $_POST['id_nhanvien'];
                $loai = $_POST['loai'];
                $tieude = $_POST['tieude'];
                $sotien = $_POST['sotien'];
                $ngay = $_POST['ngayapdung'];
                if(isset($_POST['id_thuongphat']) && $_POST['id_thuongphat'] > 0) {
                    $id_tp = $_POST['id_thuongphat'];
                    $conn->query("UPDATE thuong_phats SET id_nhanvien=$id_nv, loai='$loai', tieude='$tieude', sotien=$sotien, ngayapdung='$ngay' WHERE id_thuongphat=$id_tp");
                    echo "<p style='color:green;'>Đã cập nhật thành công!</p>";
                } else {
                    $conn->query("INSERT INTO thuong_phats (id_nhanvien, loai, tieude, sotien, ngayapdung) VALUES ($id_nv,'$loai','$tieude',$sotien,'$ngay')");
                    echo "<p style='color:green;'>Đã thêm thành công!</p>";
                }
            }

            if($can_add) {
                echo '<form method="POST" style="background:#fff; padding:15px; border-radius:5px; color:#000;">
                    <h4 style="color:#000;">Thêm/Sửa Thưởng Phạt</h4>
                    <input type="hidden" name="id_thuongphat" value="">
                    <label>Nhân viên:</label><select name="id_nhanvien">';
                $nv = $conn->query("SELECT * FROM nhan_viens");
                while($row=$nv->fetch_assoc()) { echo "<option value='{$row['id_nhanvien']}'>{$row['hoten']}</option>"; }
                echo '</select>
                    <label>Loại:</label> <select name="loai"><option value="thưởng">Thưởng</option><option value="phạt">Phạt</option></select>
                    <input type="text" name="tieude" placeholder="Tiêu đề" required>
                    <input type="number" name="sotien" placeholder="Số tiền" required>
                    <input type="date" name="ngayapdung" required value="'.date('Y-m-d').'">
                    <button type="submit" name="add_bonus">Lưu</button>
                </form>';
            }

            if(isset($_GET['delete_id']) && $can_delete) {
                $id = $_GET['delete_id'];
                $conn->query("DELETE FROM thuong_phats WHERE id_thuongphat=$id");
                echo "<p style='color:red;'>Đã xoá thành công!</p>";
            }

            // PHÂN TRANG CHO THƯỞNG PHẠT
            // 1. Đếm tổng
            $count_bonus = $conn->query("SELECT COUNT(*) as total FROM thuong_phats");
            $total_bonus = $count_bonus->fetch_assoc()['total'];
            $pages_bonus = ceil($total_bonus / $limit);
            
            // 2. Lấy dữ liệu
            $res = $conn->query("SELECT tp.*, nv.hoten FROM thuong_phats tp JOIN nhan_viens nv ON tp.id_nhanvien = nv.id_nhanvien ORDER BY tp.ngayapdung DESC LIMIT $offset, $limit");
            
            echo "<table><tr><th>Nhân viên</th><th>Loại</th><th>Tiêu đề</th><th>Số tiền</th><th>Ngày áp dụng</th><th>Hành động</th></tr>";
            while($row=$res->fetch_assoc()) {
                echo "<tr><td>{$row['hoten']}</td><td>{$row['loai']}</td><td>{$row['tieude']}</td><td>".number_format($row['sotien'])."</td><td>{$row['ngayapdung']}</td><td>";
                if($can_add) echo "<a href='#' onclick='editBonus({$row['id_thuongphat']}, {$row['id_nhanvien']}, \"{$row['loai']}\", \"{$row['tieude']}\", {$row['sotien']}, \"{$row['ngayapdung']}\")'>Sửa</a> ";
                if($can_delete) echo "<a href='pay.php?view=bonus&delete_id={$row['id_thuongphat']}' onclick='return confirm(\"Xoá mục này?\")'>Xoá</a>";
                echo "</td></tr>";
            }
            echo "</table>";

            // HIỂN THỊ PHÂN TRANG CHO BONUS
            if ($pages_bonus > 1) {
                echo '<div class="pagination">';
                $base_link_bonus = "pay.php?view=bonus&thang=$thang&nam=$nam";
                
                if ($page > 1) echo '<a href="' . $base_link_bonus . '&page=' . ($page - 1) . '">&laquo; Trước</a>';
                for ($i = 1; $i <= $pages_bonus; $i++) {
                    $active = ($i == $page) ? 'class="active"' : '';
                    echo '<a href="' . $base_link_bonus . '&page=' . $i . '" ' . $active . '>' . $i . '</a>';
                }
                if ($page < $pages_bonus) echo '<a href="' . $base_link_bonus . '&page=' . ($page + 1) . '">Sau &raquo;</a>';
                echo '</div>';
            }
        } ?>
    </div>
</div>

<footer>
    &copy; 2025 Công ty J-TECH. Mọi quyền được bảo lưu.
</footer>

<script>
function editBonus(id, id_nv, loai, tieude, sotien, ngayapdung) {
    const form = document.querySelector('form[method="POST"]');
    if(form) {
        form.id_thuongphat.value = id;
        form.id_nhanvien.value = id_nv;
        form.loai.value = loai;
        form.tieude.value = tieude;
        form.sotien.value = sotien;
        form.ngayapdung.value = ngayapdung;
        form.scrollIntoView({behavior: "smooth"});
    }
}
</script>
</body>
</html>