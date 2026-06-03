<?php
session_start();
include 'connect.php';

$thang = $_GET['thang'] ?? date('m');
$nam   = $_GET['nam'] ?? date('Y');

$sql = "
    SELECT 
        nv.hoten,
        l.luongcoban,
        l.tongphucap,
        l.tongthuong,
        l.tongphat,
        l.thue,
        l.tongluong
    FROM luongs l
    JOIN nhan_viens nv ON l.id_nhanvien = nv.id_nhanvien
    WHERE l.thang = $thang AND l.nam = $nam
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Báo cáo lương</title>
<style>
body { font-family: Arial; }
h2 { text-align:center; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { border:1px solid #000; padding:8px; text-align:center; }
th { background:#eee; }
@media print {
  button { display:none; }
}
</style>
</head>
<body>

<h2>BÁO CÁO LƯƠNG THÁNG <?= $thang ?>/<?= $nam ?></h2>

<button onclick="window.print()">🖨 In / Lưu PDF</button>

<table>
<tr>
    <th>Nhân viên</th>
    <th>Lương CB</th>
    <th>Phụ cấp</th>
    <th>Thưởng</th>
    <th>Phạt</th>
    <th>Thuế</th>
    <th>Thực nhận</th>
</tr>

<?php while($r = $result->fetch_assoc()): ?>
<tr>
    <td><?= $r['hoten'] ?></td>
    <td><?= number_format($r['luongcoban']) ?></td>
    <td><?= number_format($r['tongphucap']) ?></td>
    <td><?= number_format($r['tongthuong']) ?></td>
    <td><?= number_format($r['tongphat']) ?></td>
    <td><?= number_format($r['thue']) ?></td>
    <td><b><?= number_format($r['tongluong']) ?></b></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
