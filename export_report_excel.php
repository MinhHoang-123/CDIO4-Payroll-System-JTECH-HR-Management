<?php
session_start();
include 'connect.php';

$thang = $_GET['thang'] ?? date('m');
$nam   = $_GET['nam'] ?? date('Y');

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=bao_cao_luong_{$thang}_{$nam}.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF"; // UTF-8 BOM

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

<table border="1">
<tr style="background:#ddd;font-weight:bold">
    <th>Nhân viên</th>
    <th>Lương cơ bản</th>
    <th>Phụ cấp</th>
    <th>Thưởng</th>
    <th>Phạt</th>
    <th>Thuế</th>
    <th>Thực nhận</th>
</tr>

<?php while($r = $result->fetch_assoc()): ?>
<tr>
    <td><?= $r['hoten'] ?></td>
    <td><?= $r['luongcoban'] ?></td>
    <td><?= $r['tongphucap'] ?></td>
    <td><?= $r['tongthuong'] ?></td>
    <td><?= $r['tongphat'] ?></td>
    <td><?= $r['thue'] ?></td>
    <td><?= $r['tongluong'] ?></td>
</tr>
<?php endwhile; ?>
</table>
